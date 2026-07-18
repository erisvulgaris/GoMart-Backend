<?php

namespace App\Controllers;

use App\Libraries\CartSummery;
use App\Models\AddressModel;
use App\Models\BannerModel;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\CouponModel;
use App\Models\HomeSectionModel;
use App\Models\NotificationModel;
use App\Models\OrderModel;
use App\Models\OtpVerificationModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductModel;
use App\Models\SubcategoryModel;
use App\Models\TimeslotModel;
use App\Models\UsedCouponModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\CountryModel;
use App\Models\OrderProductModel;
use App\Models\OrderReturnRequestModel;
use App\Models\OrderStatusesModel;
use App\Models\OrderStatusListsModel;
use App\Models\SellerModel;
use App\Models\DeliverableAreaModel;
use App\Models\ProductImagesModel;
use App\Models\ProductRatingsModel;
use App\Models\ProductVariantsModel;
use App\Models\TaxModel;

use App\Libraries\GeoUtils;
use App\Models\CartsModel;
use App\Models\CityModel;
use App\Models\DeviceTokenModel;
use App\Models\FaqsModel;
use App\Models\HighlightsModel;
use App\Models\ProductSortTypeModel;
use App\Models\ProductTagModel;
use App\Models\TagsModel;
use App\Models\SmsGatewayModel;
use App\Models\DeliveryTrackingModel;
use App\Models\LanguageModel;
use App\Models\DeliveryBoyModel;
use App\Models\SellerCategoriesModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductSubcategoryModel;
use App\Models\ProductTaxModel;
use ReflectionClass;
use App\Models\SellerWalletTransactionModel;



use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Google\Service\ShoppingContent\DeliveryAreaPostalCodeRange;

class SellerAppApi extends BaseController
{
    use ResponseTrait;

    private $secretKey;

    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET');
    }

    public function getActiveCountry()
    {
        $countryModel = new CountryModel();
        $row = $countryModel->where('is_active', 1)->first();
        return $this->response->setJSON($row);
    }

    public function fetchSellerSettings()
    {
        return $this->respond([
            'sellerSettings' => $this->sellerSettings,
            'countrySettings' => $this->country
        ]);
    }

    public function login()
    {
        $request = service('request');
        $decodeData = $request->getJSON(true);

        if (empty($decodeData['email']) || empty($decodeData['password'])) {
            return $this->respond([
                "status" => 401,
                "result" => "false",
                "message"    => "Mobile and Password are required."
            ]);
        }

        $email = $decodeData['email'];
        $password = $decodeData['password'];

        $sellerModel = new SellerModel();
        $seller = $sellerModel->where('email', $email)
            ->where('is_delete', 0)
            ->first();

        if (!$seller) {
            return $this->respond([
                'status' => 401,
                'result' => false,
                'message' => 'Invalid email or password.',
            ]);
        }

        // Check if account is deleted
        if ($seller['is_delete'] == 1) {
            return $this->respond([
                'status' => 403,
                'result' => false,
                'message' => 'Account has been deleted. Contact Admin.',
            ]);
        }

        // Check if account is active (if you have an active status field)
        // if (isset($seller['status']) && $seller['status'] != 1) {
        //     return $this->respond([
        //         'status' => 403,
        //         'result' => false,
        //         'message' => 'Account is inactive. Contact Admin.',
        //     ]);
        // }

        if ($seller && password_verify($password, $seller['password'])) {
            // Generate a custom token
            $token = $this->generateToken($seller['email']);

            $deviceTokenModel = new DeviceTokenModel();

            $deviceTokenModel->insert(['user_type' => 4, 'user_id' => $seller['id'], 'app_key' => $decodeData['fcmToken']]);

            return $this->respond([
                "status" => 200,
                "result" => "true",
                "message"    => "Login successful!",
                "name"   => $seller['name'],
                "token"  => $token
            ]);
        } else {
            return $this->respond([
                "status" => 401,
                "result" => "false",
                "message"    => "Invalid email or password."
            ]);
        }
    }


    private function generateToken($deliveryMobile)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $issuedAt = time();
        $expirationTime = $issuedAt + (30 * 24 * 60 * 60); // 30 days expiration
        $payload = json_encode([
            'email' => $deliveryMobile,
            'iat' => $issuedAt, // Issued at time
            'exp' => $expirationTime // Expiration time
        ]);

        // Base64 encode header and payload
        $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        // Create signature
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secretKey, true);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    private function validateToken($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false; // Invalid token format
        }

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;

        // Verify signature
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secretKey, true);
        $expectedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        if (!hash_equals($expectedSignature, $base64UrlSignature)) {
            return false; // Invalid signature
        }

        // Decode payload
        $payload = json_decode(base64_decode($base64UrlPayload), true);

        // Validate expiration
        if (isset($payload['exp']) && time() > $payload['exp']) {
            return false; // Token expired
        }

        return $payload; // Return decoded payload if valid
    }

    private function authorizedToken()
    {
        // Check if the Authorization header exists
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->failUnauthorized('Authorization token is required');
        }

        // Extract the token from the Authorization header
        $token = str_replace('Bearer ', '', $authHeader);

        // Validate the token and get payload
        $payload = $this->validateToken($token);

        if (!$payload || !isset($payload['email'])) {
            return $this->failUnauthorized('Invalid or missing token payload');
        }

        return $payload;
    }

    private function sendMailOTP($sendEmail, $otp)
    {
        $email = \Config\Services::email();
        $settings = $this->settings;
        $mailSetting = json_decode($settings['mail_config'], true);

        $link = "<a style='background:#3E3F95;text-decoration:none !important; font-weight:700; margin:35px 0px; color:#fff;text-transform:uppercase; font-size:20px; letter-spacing: 10px; padding:10px 24px;display:inline-block;border-radius:50px;' href='#'>" . $otp . "</a>";
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => $mailSetting['host'], // Replace with your SMTP host
            'SMTPUser' => $mailSetting['username'], // Replace with your SMTP username
            'SMTPPass' => $mailSetting['password'], // Replace with your SMTP password
            'SMTPPort' => (int)$mailSetting['port'], // Common SMTP ports are 25, 465 (SSL), or 587 (TLS)
            'SMTPCrypto' => $mailSetting['encryption'], // Set to 'ssl' if needed
            'mailType' => 'html', // Set email format to HTML
            'charset'  => 'utf-8',
            'wordWrap' => true,
        ];

        // Initialize the email service with configuration
        $email->initialize($config);
        // Set up email configurations (you can also define this in app/Config/Email.php)
        $email->setFrom($mailSetting['username'], $settings['business_name']); // Sender's email and name
        $email->setTo($sendEmail); // Recipient email address
        $email->setSubject('OTP for Seller ' . $settings['business_name']);
        $email->setMessage('<!doctype html>
        <html lang="en-US">
        
        <head>
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
            <title>OTP for Seller ' . $settings['business_name'] . '</title>
            <meta name="description" content="OTP for Seller ' . $settings['logo'] . '">
            <style type="text/css">
                a:hover {text-decoration: underline !important;}
            </style>
        </head>
        
        <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
            <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
                style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: "Open Sans", sans-serif;">
                <tr>
                    <td>
                        <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                            align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                  <a href="' . base_url() . '" title="logo" target="_blank">
                                    <img width="60" src="' . base_url($settings['logo']) . '" title="' . $settings['business_name'] . '" alt="' . $settings['business_name'] . '">
                                  </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                        style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 35px;">
                                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik",sans-serif;">Your Verification Code</h1>
                                                <span
                                                    style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                    We’re excited to have you on board! To confirm your account, please use the following One-Time Password (OTP):
                                                </p>
                                                ' . $link . '
                                                <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                    For security reasons, please do not share it with anyone.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong> <a href="' . base_url() . '">' . base_url() . '</a> </strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        
        </html>');
        $email->setMailType('html');
        $email->send();
    }

    public function sendForgetPasswordOTP()
    {
        $dataInput = $this->request->getJSON(true); // Parse JSON input
        date_default_timezone_set($this->timeZone['timezone']); // Set the timezone

        // Validation Rules
        $validationRules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
            ]
        ];

        // Validate Input
        if (!$this->validate($validationRules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors(), // Return detailed errors
            ]);
        }

        $sellerModel = new SellerModel();
        $otpVerificationModel = new OtpVerificationModel();

        // Check if the email already exists
        $existingUser = $sellerModel->where('email', $dataInput['email'])->first();

        if (!$existingUser) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No account associated with given email ID.',
            ]);
        }

        if ($existingUser['is_delete'] == 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Account has been deleted.']);
        }

        // Send OTP and return response
        $otp = random_int(100000, 999999);

        $otpData = [
            'email' => $dataInput['email'],
            'otp' => $otp,
            'verify_by' => 'email',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $otpVerificationModel->insert($otpData);

        $this->sendMailOTP($dataInput['email'], $otp); // Send OTP email
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'OTP sent to registered Email ID.',
        ]);
    }

    public function verifyForgetPasswordOTP()
    {
        // Set the timezone
        date_default_timezone_set($this->timeZone['timezone']);

        // Retrieve input data
        $dataInput = $this->request->getJSON(true);

        // Validate required fields
        if (!isset($dataInput['email']) || !isset($dataInput['otp'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email and OTP are required.'
            ]);
        }

        // Load models
        $sellerModel = new SellerModel();
        $otpVerificationModel = new OtpVerificationModel();

        // Fetch the OTP verification record
        $existingOtp = $otpVerificationModel->where('email', $dataInput['email'])
            ->where('otp', $dataInput['otp'])
            ->orderBy('id', 'desc')
            ->first();

        if ($existingOtp) {
            // OTP matches, proceed to verify the user
            $sellerInfo = $sellerModel->where('email', $dataInput['email'])->where('is_delete', 0)->first();

            if ($sellerInfo) {
                // Return success response
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'OTP Verified Successfully',
                ]);
            } else {
                // Seller not found
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Seller not found. Please try again.',
                ]);
            }
        } else {
            // OTP does not match
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }
    }

    public function updatePassword()
    {
        $dataInput = $this->request->getJSON(true);

        $email = $dataInput['email'];
        $pass = $dataInput['password'];
        $cpass = $dataInput['confirmPassword'];
        // Validate password match
        if ($pass !== $cpass) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'The passwords do not match. Please re-enter the passwords.']);
        }

        // Check if the reset link token and email are valid
        $sellerModel = new SellerModel();

        $sellerInfo = $sellerModel->where('email', $email)->where('is_delete', 0)->first();

        // Check if user exists and handle specific conditions
        if (!$sellerInfo) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No account found with this email.']);
        }

        // Check if the account is deleted
        if ($sellerInfo['is_delete'] == 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'This account has been deleted.']);
        }

        // At this point, all conditions are met and the user can reset the password
        $data1 = [
            'password' => password_hash($pass, PASSWORD_BCRYPT),
        ];

        // Update the user's password
        $sellerModel->set($data1)->update($sellerInfo['id']);
        $token = $this->generateToken($dataInput['email']);


        $deviceTokenModel = new DeviceTokenModel();

        $deviceTokenModel->insert(['user_type' => 4, 'user_id' => $sellerInfo['id'], 'app_key' => $dataInput['fcmToken']]);


        return $this->response->setJSON(['status' => 'success', 'message' => 'Password changed successfully.', 'token' => $token]);
    }

    public function fetchAllCategories()
    {
        $dataInput = $this->request->getJSON(true); // Parse JSON input
        $sellerModel = new SellerModel();
        $authHeader = $this->request->getHeaderLine('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }
            if (isset($payload['email'])) {
                $sellerInfo = $sellerModel
                    ->where('is_delete', 0)
                    ->where('email', $payload['email'])
                    ->first();
            }

            // If seller wasn't found, set default id
            if (!$sellerInfo) {
                return $this->respond([
                    'status' => 404,
                    'result' => 'false',
                    'message' => 'Seller not found'
                ]);
            }
        } else {
            return $this->respond([
                'status' => 404,
                'result' => 'false',
                'message' => 'Seller not found'
            ]);
        }

        $categoryModel = new CategoryModel();



        // Fetch all categories ordered by `row_order` in ascending order
        $categories = $categoryModel->getCategoriesForSellerWithSubCount($sellerInfo['id']);

        // Append base_url to category_img
        foreach ($categories as &$category) {
            $category['category_img'] = base_url($category['category_img']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $categories,
        ]);
    }

    public function fetchAllSubcategories()
    {
        $dataInput = $this->request->getJSON(true); // Parse JSON input
        $sellerModel = new SellerModel();
        $authHeader = $this->request->getHeaderLine('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }
            if (isset($payload['email'])) {
                $sellerInfo = $sellerModel
                    ->where('is_delete', 0)
                    ->where('email', $payload['email'])
                    ->first();
            }

            // If seller wasn't found, set default id
            if (!$sellerInfo) {
                return $this->respond([
                    'status' => 404,
                    'result' => 'false',
                    'message' => 'Seller not found'
                ]);
            }
        } else {
            return $this->respond([
                'status' => 404,
                'result' => 'false',
                'message' => 'Seller not found'
            ]);
        }
        $subcategoryModel = new SubcategoryModel();

        // Fetch all subcategories ordered by `row_order` in ascending order
        $categoryId = $dataInput['categoryId'] ?? 0;

        $subcategories = $subcategoryModel->getSubcategoriesWithDetailsForSeller(
            $sellerInfo['id'],
            $categoryId
        );

        foreach ($subcategories as &$subcategory) {
            $subcategory['img'] = base_url($subcategory['img']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $subcategories,
        ]);
    }

    public function registerVendor()
    {
        $dataInput = $this->request->getJSON(true);

        // Validate password match
        $pass = $dataInput['password'] ?? null;
        $cpass = $dataInput['confirmPassword'] ?? null;

        if (!$pass || !$cpass) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Password and confirm password are required.'
            ]);
        }

        if ($pass !== $cpass) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'The passwords do not match. Please re-enter the passwords.'
            ]);
        }

        // Validate required fields
        $requiredFields = ['name', 'email', 'mobile'];
        foreach ($requiredFields as $field) {
            if (empty($dataInput[$field])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => ucfirst($field) . ' is required.'
                ]);
            }
        }

        $sellerModel = new SellerModel();

        // Check if email already exists (but not approved yet)
        $emailExists1 = $sellerModel->where('email', $dataInput['email'])
            ->where('is_delete', 0)
            ->where('city_id !=', 0)
            ->where('deliverable_area_id !=', 0)
            ->first();

        if ($emailExists1) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'All ready register with same email, use different one'
            ]);
        }

        // Check if email already exists (but not approved yet)
        $emailExists = $sellerModel->where('email', $dataInput['email'])
            ->where('is_delete', 0)
            ->where('status', 0)
            ->first();

        $isExistingVendor = false;
        if ($emailExists) {
            $isExistingVendor = true;
            $sellerId = $emailExists['id'];
        }

        // Check if mobile already exists (only if vendor doesn't exist yet)
        if (!$isExistingVendor) {
            $mobileExists = $sellerModel->where('mobile', $dataInput['mobile'])
                ->where('is_delete', 0)
                ->first();

            if ($mobileExists) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This mobile number is already registered.'
                ]);
            }
        }

        // Only generate slug and insert if vendor doesn't exist
        if (!$isExistingVendor) {
            // Generate unique slug
            $slug = url_title($dataInput['name'], '-', true);
            $slugExists = $sellerModel->where('slug', $slug)
                ->where('is_delete', 0)
                ->first();

            if ($slugExists) {
                $slug = $slug . '-' . time();
            }

            // Prepare seller data
            $sellerData = [
                'name' => $dataInput['name'],
                'email' => $dataInput['email'],
                'mobile' => $dataInput['mobile'],
                'password' => password_hash($dataInput['password'], PASSWORD_BCRYPT),
                'store_name' => $dataInput['store_name'] ?? $dataInput['name'],
                'store_address' => $dataInput['store_address'] ?? '',
                'city_id' => $dataInput['city_id'] ?? 0,
                'deliverable_area_id' => $dataInput['deliverable_area_id'] ?? 0,
                'slug' => $slug,
                'balance' => 0,
                'status' => 0,
                'is_delete' => 0,
                'vendor_type' => 0,
                'commission' => 1,
                'require_products_approval' => 1,
                'account_number' => $dataInput['account_number'] ?? '',
                'bank_ifsc_code' => $dataInput['bank_ifsc_code'] ?? '',
                'account_name' => $dataInput['account_name'] ?? '',
                'branch' => $dataInput['branch'] ?? '',
                'bank_name' => $dataInput['bank_name'] ?? '',
                'pan_number' => $dataInput['pan_number'] ?? '',
                'tax_number' => $dataInput['tax_number'] ?? '',
                'tax_name' => $dataInput['tax_name'] ?? '',
                'map_address' => $dataInput['map_address'] ?? '',
                'latitude' => $dataInput['latitude'] ?? 0,
                'longitude' => $dataInput['longitude'] ?? 0,
                'fcm_app_key' => $dataInput['fcm_app_key'] ?? '',
                'national_id_proof' => $dataInput['national_id_proof'] ?? '',
                'address_proof' => $dataInput['address_proof'] ?? '',
                'view_customer_details' => 0,
                'order_status_permission' => 0,
                'status_reason' => '',
                'registered_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Insert seller data
            $sellerId = $sellerModel->insert($sellerData);

            if (!$sellerId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to register vendor. Please try again.'
                ]);
            }
        }

        // Generate and send OTP (whether new vendor or existing pending vendor)
        $otp = random_int(100000, 999999);
        $otpVerificationModel = new OTPVerificationModel();

        $otpData = [
            'email' => $dataInput['email'],
            'otp' => $otp,
            'verify_by' => 'email',
            'seller_id' => $sellerId,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $otpVerificationModel->insert($otpData);

        // Send OTP email
        $this->sendMailOTP($dataInput['email'], $otp);

        $message = 'Vendor registered successfully. OTP sent to your email.';

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function verifyOTP()
    {
        // Set the timezone
        date_default_timezone_set($this->timeZone['timezone']);

        // Retrieve input data
        $dataInput = $this->request->getJSON(true);

        // Validate required fields
        if (!isset($dataInput['email']) || !isset($dataInput['otp'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email and OTP are required.'
            ]);
        }

        // Load models
        $sellerModel = new SellerModel();
        $otpVerificationModel = new OtpVerificationModel();

        // Fetch the OTP verification record
        $existingOtp = $otpVerificationModel->where('email', $dataInput['email'])
            ->where('otp', $dataInput['otp'])
            ->orderBy('id', 'desc')
            ->first();

        if ($existingOtp) {
            // OTP matches, proceed to verify the user
            $sellerInfo = $sellerModel->where('email', $dataInput['email'])->where('is_delete', 0)->first();

            if ($sellerInfo) {
                // Return success response
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'OTP Verified Successfully',
                ]);
            } else {
                // Seller not found
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Seller not found. Please try again.',
                ]);
            }
        } else {
            // OTP does not match
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }
    }

    private function pointInPolygon($latitude, $longitude, $boundaryPoints)
    {
        $n = count($boundaryPoints);
        $inside = false;

        $p1x = $boundaryPoints[0]['latitude'];
        $p1y = $boundaryPoints[0]['longitude'];

        for ($i = 1; $i <= $n; $i++) {
            $p2x = $boundaryPoints[$i % $n]['latitude'];
            $p2y = $boundaryPoints[$i % $n]['longitude'];

            if ($longitude > min($p1y, $p2y)) {
                if ($longitude <= max($p1y, $p2y)) {
                    if ($latitude <= max($p1x, $p2x)) {
                        if ($p1y != $p2y) {
                            $xinters = ($longitude - $p1y) * ($p2x - $p1x) / ($p2y - $p1y) + $p1x;
                            if ($p1x == $p2x || $latitude <= $xinters) {
                                $inside = !$inside;
                            }
                        }
                    }
                }
            }
            $p1x = $p2x;
            $p1y = $p2y;
        }

        return $inside;
    }

    public function registerVendorFinal()
    {
        // $dataInput = $this->request->getJSON(true);

        $email = $this->request->getPost('email');
        $yourName = $this->request->getPost('your_name');
        $storeName = $this->request->getPost('store_name');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $mapAddress = $this->request->getPost('map_address');
        $categories = $this->request->getPost('categories');

        // Validate required fields
        if (empty($email) || empty($yourName) || empty($storeName)) {
            return $this->respond([
                'status' => false,
                'message' => 'Email, name, and store name are required.'
            ], 400);
        }

        if (empty($latitude) || empty($longitude)) {
            return $this->respond([
                'status' => false,
                'message' => 'Location (latitude and longitude) is required.'
            ], 400);
        }

        $sellerModel = new SellerModel();
        $deliverableAreaModel = new DeliverableAreaModel();
        $sellerCategoriesModel = new SellerCategoriesModel();

        $existingSeller = $sellerModel
            ->where('email', $email)
            ->where('is_delete', 0)
            ->first();

        if (!$existingSeller) {
            return $this->respond([
                'status' => false,
                'message' => 'Seller account not found. Please complete initial registration first.'
            ], 404);
        }

        try {
            // Find deliverable area and city
            $areaData = $this->findDeliverableArea((float)$latitude, (float)$longitude);

            if (!$areaData) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Selected location is not in a deliverable area.'
                ], 400);
            }

            $deliverableAreaId = $areaData['id'];
            $cityId = $areaData['city_id'];

            // Prepare update data
            $updateData = [
                'name' => $yourName,
                'store_name' => $storeName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'map_address' => $mapAddress,
                'city_id' => $cityId,
                'deliverable_area_id' => $deliverableAreaId,
                'status' => 0, // Pending approval
            ];

            // Handle file uploads
            $uploadDir = FCPATH . 'uploads/seller/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Upload National ID Proof
            if ($this->request->getFile('national_id_proof')) {
                $nationalIdFile = $this->request->getFile('national_id_proof');
                if ($nationalIdFile->isValid() && !$nationalIdFile->hasMoved()) {
                    $newName = 'national_id_' . $existingSeller['id'] . '_' . time() . '.' . $nationalIdFile->getExtension();
                    $nationalIdFile->move($uploadDir, $newName);
                    $updateData['national_id_proof'] = 'uploads/seller/' . $newName;
                }
            }

            // Upload Address Proof
            if ($this->request->getFile('address_proof')) {
                $addressFile = $this->request->getFile('address_proof');
                if ($addressFile->isValid() && !$addressFile->hasMoved()) {
                    $newName = 'address_proof_' . $existingSeller['id'] . '_' . time() . '.' . $addressFile->getExtension();
                    $addressFile->move($uploadDir, $newName);
                    $updateData['address_proof'] = 'uploads/seller/' . $newName;
                }
            }


            // Update seller information
            $sellerModel->update($existingSeller['id'], $updateData);

            // Handle categories for pet_food and all vendor types
            $categoryIds = json_decode($categories, true);

            // Delete existing categories
            $sellerCategoriesModel->where('seller_id', $existingSeller['id'])->delete();

            // Insert new categories
            foreach ($categoryIds as $categoryId) {
                $sellerCategoriesModel->insert([
                    'seller_id' => $existingSeller['id'],
                    'category_id' => $categoryId,
                ]);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Vendor registration completed successfully.',
                'data' => [
                    'seller_id' => $existingSeller['id'],
                    'email' => $email,
                    'deliverable_area_id' => $deliverableAreaId,
                    'city_id' => $cityId,
                ]
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Vendor Registration Error: ' . $e->getMessage());
            return $this->respond([
                'status' => false,
                'message' => 'An error occurred during registration. Please try again.',
                'error' => $e->getMessage() // Remove in production
            ], 500);
        }
    }

    private function findDeliverableArea($latitude, $longitude)
    {
        $deliverableAreaModel = new DeliverableAreaModel();

        $deliverableAreas = $deliverableAreaModel
            ->where('is_delete', 0)
            ->findAll();

        foreach ($deliverableAreas as $area) {
            $boundaryPoints = json_decode($area['boundry_points'], true);

            // Check if point is within polygon
            if ($this->pointInPolygon($latitude, $longitude, $boundaryPoints)) {
                return [
                    'id' => $area['id'],
                    'city_id' => $area['city_id'],
                    'title' => $area['deliverable_area_title'],
                ];
            }
        }

        return null;
    }

    public function fetchProducts()
    {
        $sellerModel = new SellerModel();
        $payload = $this->authorizedToken();

        $productImagesModel        = new ProductImagesModel();
        $productCategoriesModel    = new ProductCategoryModel();
        $productSubCategoriesModel = new ProductSubcategoryModel();
        $productTaxesModel         = new ProductTaxModel();
        $taxModel                  = new TaxModel(); // your tax model
        $categoryModel             = new CategoryModel();
        $subcategoryModel          = new SubcategoryModel();

        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized',
            ]);
        }

        $request = \Config\Services::request();

        // Sanitize and clamp pagination inputs
        $page  = max(1, (int) ($request->getPost('page')  ?? 1));
        $limit = 50;
        // $limit = min(50, max(1, (int) ($request->getPost('limit') ?? 10)));
        $offset = ($page - 1) * $limit;

        $productModel         = new ProductModel();
        $productVariantsModel = new ProductVariantsModel();
        $categoryModel        = new CategoryModel();
        $subcategoryModel     = new SubcategoryModel();

        $baseQuery = $productModel
            ->where('seller_id', $user['id'])
            ->where('is_delete', 0);

        // Total count for pagination meta
        $totalProducts = $productModel
            ->where('seller_id', $user['id'])
            ->where('is_delete', 0)
            ->countAllResults();

        $totalPages = $totalProducts > 0 ? (int) ceil($totalProducts / $limit) : 1;

        // Return empty early if page exceeds total
        if ($page > $totalPages) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page'     => $limit,
                    'total'        => $totalProducts,
                    'total_pages'  => $totalPages,
                ],
            ]);
        }

        // Fetch paginated products ordered consistently to prevent row shifting
        $products = $productModel
            ->where('seller_id', $user['id'])
            ->where('is_delete', 0)
            ->orderBy('id', 'ASC')
            ->limit($limit, $offset)
            ->findAll();

        // Deduplicate by id (safety net)
        $seen     = [];
        $products = array_values(array_filter($products, function ($p) use (&$seen) {
            if (isset($seen[$p['id']])) return false;
            $seen[$p['id']] = true;
            return true;
        }));

        // Enrich each product
        foreach ($products as &$product) {
            $product['main_img'] = base_url($product['main_img']);

            // ── Multiple Categories ──────────────────────────────────────────
            $catLinks = $productCategoriesModel
                ->where('product_id', $product['id'])
                ->findAll();

            $categories = [];
            foreach ($catLinks as $link) {
                $cat = $categoryModel->find((int) $link['category_id']);
                if ($cat) {
                    $categories[] = [
                        'id'   => $cat['id'],
                        'name' => $cat['category_name'],
                    ];
                }
            }
            $product['categories'] = $categories;

            // ── Multiple Subcategories ───────────────────────────────────────
            $subLinks = $productSubCategoriesModel
                ->where('product_id', $product['id'])
                ->findAll();

            $subcategories = [];
            foreach ($subLinks as $link) {
                $sub = $subcategoryModel->find((int) $link['subcategory_id']);
                if ($sub) {
                    $subcategories[] = [
                        'id'   => $sub['id'],
                        'name' => $sub['name'],
                    ];
                }
            }
            $product['subcategories'] = $subcategories;

            // ── Multiple Taxes ───────────────────────────────────────────────
            $taxLinks = $productTaxesModel
                ->where('product_id', $product['id'])
                ->findAll();

            $taxes = [];
            foreach ($taxLinks as $link) {
                $tax = $taxModel->find((int) $link['tax_id']);
                if ($tax) {
                    $taxes[] = [
                        'id'         => $tax['id'],
                        'title'      => $tax['tax'],
                        'percentage' => $tax['percentage'],
                    ];
                }
            }
            $product['taxes']                = $taxes;
            $product['tax_included_in_price'] = $product['tax_included_in_price'];

            // ── Variants with Images ─────────────────────────────────────────
            $variants = $productVariantsModel
                ->where('product_id', $product['id'])
                ->where('is_delete', 0)
                ->orderBy('id', 'ASC')
                ->findAll();

            foreach ($variants as &$variant) {
                $variant['discount_percentage'] =
                    ($variant['discounted_price'] == 0 || $variant['price'] == 0)
                    ? 0
                    : round((($variant['price'] - $variant['discounted_price']) / $variant['price']) * 100);

                // Variant image
                $variantImage = $productImagesModel
                    ->where('product_id', $product['id'])
                    ->where('product_variant_id', $variant['id'])
                    ->first();

                $variant['image'] = $variantImage
                    ? base_url($variantImage['image'])
                    : null;
            }
            unset($variant);

            $product['variants'] = $variants;
        }
        unset($product);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $limit,
                'total'        => $totalProducts,
                'total_pages'  => $totalPages,
            ],
        ]);
    }

    public function fetchCategories()
    {
        $sellerModel = new SellerModel();
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }
        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        $sellerCategoriesModel = new SellerCategoriesModel();
        $sellerCategories = $sellerCategoriesModel
            ->where('seller_id', $user['id'])
            ->findAll();

        $categoryModel = new CategoryModel();
        $categoryIds = array_column($sellerCategories, 'category_id');

        $categories = $categoryModel
            ->select('id, category_name')
            ->whereIn('id', $categoryIds)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    public function fetchsubCategoriesByCategoryId()
    {
        $dataInput = $this->request->getJSON(true);

        $subcategoryModel = new SubcategoryModel();
        $subcategory = $subcategoryModel->where('category_id', $dataInput['category_id'])->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $subcategory
        ]);
    }

    public function fetchBrand()
    {
        $dataInput = $this->request->getJSON(true);

        $brandModel = new BrandModel();
        $brand = $brandModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $brand
        ]);
    }

    public function fetchTaxes()
    {
        $taxModel = new TaxModel();
        $tax = $taxModel->where('is_active', 1)->where('is_delete', 0)->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $tax
        ]);
    }

    public function addProduct()
    {
        $sellerModel = new SellerModel();
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }
        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized seller',
            ], 401);
        }

        $productModel              = new ProductModel();
        $productVariantsModel      = new ProductVariantsModel();
        $productImagesModel        = new ProductImagesModel();
        $productTagModel           = new ProductTagModel();
        $tagModel                  = new TagsModel();
        $productCategoriesModel    = new ProductCategoryModel();
        $productSubCategoriesModel = new ProductSubcategoryModel();
        $productTaxesModel         = new ProductTaxModel();
        $sellerCategoriesModel     = new SellerCategoriesModel();

        // Validate input — category_id and tax_id are JSON-encoded arrays from the app
        $rules = [
            'product_name' => 'required|string',
            'category_id'  => 'required|string',
            'brand_id'     => 'required|integer',
            'description'  => 'required|string',
            'variations'   => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        try {
            $productName        = $this->request->getPost('product_name');
            $categoryIdsJson    = $this->request->getPost('category_id');
            $subcategoryIdsJson = $this->request->getPost('subcategory_id');
            $brandId            = $this->request->getPost('brand_id');
            $description        = $this->request->getPost('description');
            $publishStatus      = $this->request->getPost('publish_status') ?? 'publish';
            $isPopular          = $this->request->getPost('popular') ?? 0;
            $isDealOfDay        = $this->request->getPost('deal_of_the_day') ?? 0;
            $manufacturer       = $this->request->getPost('manufacturer');
            $madeIn             = $this->request->getPost('made_in');
            $taxIdsJson         = $this->request->getPost('tax_id');
            $taxIncludedInPrice = $this->request->getPost('tax_included_in_price') ?? 1;
            $isReturnable       = $this->request->getPost('is_returnable') ?? 0;
            $returnDays         = $this->request->getPost('return_days') ?? 0;
            $fssaiLicNo         = $this->request->getPost('fssai_lic_no');
            $totalAllowedQty    = $this->request->getPost('total_allowed_quantity');
            $tagsJson           = $this->request->getPost('tags');
            $variationsJson     = $this->request->getPost('variations');

            // Decode JSON arrays sent from the app
            $categoryIds    = json_decode($categoryIdsJson, true) ?? [];
            $subcategoryIds = json_decode($subcategoryIdsJson, true) ?? [];
            $taxIds         = json_decode($taxIdsJson, true) ?? [];
            $tags           = json_decode($tagsJson, true) ?? [];
            $variations     = json_decode($variationsJson, true) ?? [];

            if (empty($categoryIds)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'At least one category is required',
                ], 422);
            }

            if (empty($variations)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'At least one variation is required',
                ], 422);
            }

            // Handle main product image upload
            $mainImage = $this->request->getFile('product_image');
            $imagePath = null;
            if ($mainImage && $mainImage->isValid() && !$mainImage->hasMoved()) {
                $newName = $mainImage->getRandomName();
                $mainImage->move(FCPATH . 'uploads/products', $newName);
                $imagePath = 'uploads/products/' . $newName;
            }

            // Generate unique slug
            $slug_prev = str_replace(' ', '-', $productName);
            $slug      = preg_replace('/[^A-Za-z0-9-]/', '', strtolower($slug_prev));
            $slug1     = $slug;
            $check     = true;
            $x         = 1;
            while ($check) {
                $duplicateSlug = $productModel->where('slug', $slug1)->countAllResults();
                if ($duplicateSlug > 0) {
                    $slug1 = $slug . $x;
                } else {
                    $check = false;
                }
                $x++;
            }

            // Insert product
            // category_id / subcategory_id / tax_id are stored in junction tables below
            $productData = [
                'brand_id'               => $brandId,
                'seller_id'              => $user['id'],
                'product_name'           => $productName,
                'slug'                   => $slug1,
                'main_img'               => $imagePath,
                'description'            => $description,
                'popular'                => $isPopular,
                'deal_of_the_day'        => $isDealOfDay,
                'manufacturer'           => $manufacturer,
                'made_in'                => $madeIn,
                'total_allowed_quantity' => $totalAllowedQty,
                'tax_included_in_price'  => $taxIncludedInPrice,
                'fssai_lic_no'           => $fssaiLicNo,
                'return_days'            => $returnDays,
                'is_returnable'          => $isReturnable,
                'status'                 => $publishStatus === 'publish' ? 1 : 0,
                'added_by_seller'        => 1,
                'date'                   => date('Y-m-d H:i:s'),
            ];

            $productId = $productModel->insert($productData);

            if (!$productId) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Failed to create product',
                ], 500);
            }

            // Insert product_categories (multiple) + sync seller_categories
            foreach ($categoryIds as $catId) {
                $productCategoriesModel->insert([
                    'product_id'  => $productId,
                    'category_id' => (int) $catId,
                ]);

                $alreadyLinked = $sellerCategoriesModel
                    ->where('seller_id', $user['id'])
                    ->where('category_id', (int) $catId)
                    ->countAllResults();
                if (!$alreadyLinked) {
                    $sellerCategoriesModel->insert([
                        'seller_id'   => $user['id'],
                        'category_id' => (int) $catId,
                    ]);
                }
            }

            // Insert product_subcategories (multiple)
            foreach ($subcategoryIds as $subCatId) {
                $productSubCategoriesModel->insert([
                    'product_id'     => $productId,
                    'subcategory_id' => (int) $subCatId,
                ]);
            }

            // Insert product_taxes (multiple)
            foreach ($taxIds as $taxId) {
                $productTaxesModel->insert([
                    'product_id' => $productId,
                    'tax_id'     => (int) $taxId,
                ]);
            }

            // Insert variations and their individual images
            foreach ($variations as $idx => $variation) {
                $isUnlimitedStock = ($variation['stock'] === '' || $variation['stock'] === null) ? 1 : 0;

                $variationData = [
                    'product_id'         => $productId,
                    'title'              => $variation['title'],
                    'price'              => $variation['price'],
                    'discounted_price'   => $variation['offerPrice'] ?? null,
                    'stock'              => $variation['stock'] ?? 0,
                    'is_unlimited_stock' => $isUnlimitedStock,
                    'status'             => 1,
                ];
                $variantId = $productVariantsModel->insert($variationData);

                // Each variation may carry its own image (variation_image_0, variation_image_1, …)
                $variantImageFile = $this->request->getFile('variation_image_' . $idx);
                if ($variantImageFile && $variantImageFile->isValid() && !$variantImageFile->hasMoved()) {
                    $variantImageName = $variantImageFile->getRandomName();
                    $variantImageFile->move(FCPATH . 'uploads/products/variants', $variantImageName);
                    $productImagesModel->insert([
                        'product_id'         => $productId,
                        'product_variant_id' => $variantId,
                        'image'              => 'uploads/products/variants/' . $variantImageName,
                    ]);
                }
            }

            // Insert main product image into product_images (no variant link)
            if ($imagePath) {
                $productImagesModel->insert([
                    'product_id'         => $productId,
                    'product_variant_id' => null,
                    'image'              => $imagePath,
                ]);
            }

            // Insert tags
            if (!empty($tags)) {
                foreach ($tags as $tagName) {
                    $existingTag = $tagModel->where('name', $tagName)->first();
                    if ($existingTag) {
                        $tagId = $existingTag['id'];
                    } else {
                        $tagId = $tagModel->insert([
                            'name'       => $tagName,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    $productTagModel->insert([
                        'product_id' => $productId,
                        'tag_id'     => $tagId,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Product added successfully',
                'product_id' => $productId,
            ], 201);
        } catch (\Exception $e) {
            log_message('error', 'Add Product Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Server error',
            ], 500);
        }
    }

    public function deleteProduct()
    {
        $sellerModel = new SellerModel();
        $productModel = new ProductModel();
        $productVariantsModel = new ProductVariantsModel();

        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $seller = $sellerModel
            ->where([
                'email'     => $payload['email'],
                'status'    => 1,
                'is_delete' => 0,
            ])
            ->first();

        if (!$seller) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized seller',
            ])->setStatusCode(401);
        }

        $dataInput = $this->request->getJSON(true);
        if (empty($dataInput['product_id'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Product ID is required',
            ])->setStatusCode(422);
        }

        $product = $productModel
            ->where([
                'id'        => $dataInput['product_id'],
                'seller_id' => $seller['id'],
                'is_delete' => 0,
            ])
            ->first();

        if (!$product) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Product not found or access denied',
            ])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update product
            $productModel->update($product['id'], [
                'status'    => 0,
                'is_delete' => 1,
            ]);

            // Update product variants
            $db->table('product_variants')
                ->where('product_id', $product['id'])
                ->update([
                    'is_delete' => 1,
                ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Failed to delete product',
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Product deleted successfully',
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error deleting product: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    public function productDetails()
    {
        $sellerModel              = new SellerModel();
        $productModel             = new ProductModel();
        $productVariantsModel     = new ProductVariantsModel();
        $productImagesModel       = new ProductImagesModel();
        $productTagModel          = new ProductTagModel();
        $categoryModel            = new CategoryModel();
        $subcategoryModel         = new SubcategoryModel();
        $brandModel               = new BrandModel();
        $taxModel                 = new TaxModel();
        $tagModel                 = new TagsModel();
        $productCategoriesModel   = new ProductCategoryModel();
        $productSubCategoriesModel = new ProductSubcategoryModel();
        $productTaxesModel        = new ProductTaxModel();

        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized seller',
            ], 401);
        }

        // Get product_id from request
        $dataInput = $this->request->getJSON(true);
        if (empty($dataInput['product_id'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Product ID is required',
            ], 422);
        }

        $productId = $dataInput['product_id'];

        // Fetch product
        $product = $productModel
            ->where([
                'id' => $productId,
                'seller_id' => $user['id'],
                'is_delete' => 0,
            ])
            ->first();

        if (!$product) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Product not found or access denied',
            ], 404);
        }

        try {
            // Get multiple categories from junction table
            $productCatRows = $productCategoriesModel
                ->where('product_id', $productId)
                ->findAll();
            $categories = [];
            foreach ($productCatRows as $pcRow) {
                $cat = $categoryModel->where('id', $pcRow['category_id'])->first();
                if ($cat) {
                    $categories[] = [
                        'id'            => $cat['id'],
                        'category_name' => $cat['category_name'],
                        'name'          => $cat['category_name'],
                    ];
                }
            }
            $product['categories'] = $categories;

            // Get multiple subcategories from junction table
            $productSubCatRows = $productSubCategoriesModel
                ->where('product_id', $productId)
                ->findAll();
            $subcategories = [];
            foreach ($productSubCatRows as $psRow) {
                $subCat = $subcategoryModel->where('id', $psRow['subcategory_id'])->first();
                if ($subCat) {
                    $subcategories[] = [
                        'id'   => $subCat['id'],
                        'name' => $subCat['name'],
                    ];
                }
            }
            $product['subcategories'] = $subcategories;

            // Get multiple taxes from junction table
            $productTaxRows = $productTaxesModel
                ->where('product_id', $productId)
                ->findAll();
            $taxes = [];
            foreach ($productTaxRows as $ptRow) {
                $tax = $taxModel->where('id', $ptRow['tax_id'])->first();
                if ($tax) {
                    $taxes[] = [
                        'id'         => $tax['id'],
                        'tax'        => $tax['tax'],
                        'name'       => $tax['tax'],
                        'percentage' => $tax['percentage'] ?? 0,
                    ];
                }
            }
            $product['taxes'] = $taxes;

            // Get brand details
            $brand = $brandModel->where('id', $product['brand_id'])->first();
            $product['brand'] = $brand ? [
                'id'   => $brand['id'],
                'name' => $brand['brand'],
            ] : null;

            // Format main image
            if ($product['main_img']) {
                $product['main_img'] = base_url($product['main_img']);
            }

            // Get all product images (main + variant)
            $images = $productImagesModel
                ->where('product_id', $productId)
                ->findAll();
            $product['images'] = array_map(function ($img) {
                return [
                    'id'                 => $img['id'],
                    'product_variant_id' => $img['product_variant_id'] ?? null,
                    'image'              => base_url($img['image']),
                ];
            }, $images);

            // Build a lookup of variant_id => image_url for variation images
            $variantImageMap = [];
            foreach ($images as $img) {
                if (!empty($img['product_variant_id'])) {
                    $variantImageMap[$img['product_variant_id']] = base_url($img['image']);
                }
            }

            // Get variations with discount percentage and their images
            $variations = $productVariantsModel
                ->where('product_id', $productId)
                ->where('is_delete', 0)
                ->findAll();

            $formattedVariations = [];
            foreach ($variations as $variation) {
                $discountPercentage = ($variation['discounted_price'] == 0 || $variation['price'] == 0)
                    ? 0
                    : round((($variation['price'] - $variation['discounted_price']) / $variation['price']) * 100);

                $formattedVariations[] = [
                    'id'                 => $variation['id'],
                    'title'              => $variation['title'],
                    'price'              => (float)$variation['price'],
                    'discounted_price'   => (float)$variation['discounted_price'],
                    'discount_percentage' => $discountPercentage,
                    'stock'              => (int)$variation['stock'],
                    'is_unlimited_stock' => (int)$variation['is_unlimited_stock'],
                    'status'             => (int)$variation['status'],
                    'image_url'          => $variantImageMap[$variation['id']] ?? null,
                ];
            }
            $product['variations'] = $formattedVariations;

            // Get tags
            $productTags = $productTagModel
                ->where('product_id', $productId)
                ->findAll();
            $tags = [];
            foreach ($productTags as $pt) {
                $tag = $tagModel->where('id', $pt['tag_id'])->first();
                if ($tag) {
                    $tags[] = [
                        'id'   => $tag['id'],
                        'name' => $tag['name'],
                    ];
                }
            }
            $product['tags'] = $tags;

            // Format boolean fields
            $product['popular']          = (int)$product['popular'];
            $product['deal_of_the_day']  = (int)$product['deal_of_the_day'];
            $product['is_returnable']    = (int)$product['is_returnable'];
            $product['status']           = (int)$product['status'];
            $product['added_by_seller']  = (int)$product['added_by_seller'];

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $product,
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Product Details Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Server error',
            ], 500);
        }
    }

    public function updateProduct()
    {
        $sellerModel               = new SellerModel();
        $productModel              = new ProductModel();
        $productVariantsModel      = new ProductVariantsModel();
        $productImagesModel        = new ProductImagesModel();
        $productTagModel           = new ProductTagModel();
        $tagModel                  = new TagsModel();
        $productCategoriesModel    = new ProductCategoryModel();
        $productSubCategoriesModel = new ProductSubcategoryModel();
        $productTaxesModel         = new ProductTaxModel();
        $sellerCategoriesModel     = new SellerCategoriesModel();

        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized seller',
            ], 401);
        }

        // Get product_id
        $productId = $this->request->getPost('product_id');
        if (empty($productId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Product ID is required',
            ], 422);
        }

        // Check if product exists and belongs to seller
        $product = $productModel
            ->where([
                'id'        => $productId,
                'seller_id' => $user['id'],
                'is_delete' => 0,
            ])
            ->first();

        if (!$product) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Product not found or access denied',
            ], 404);
        }

        // Validate input — category_id and tax_id are JSON-encoded arrays
        $rules = [
            'product_name' => 'required|string',
            'category_id'  => 'required|string',
            'brand_id'     => 'required|integer',
            'description'  => 'required|string',
            'variations'   => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors(),
            ], 422);
        }

        try {
            $productName        = $this->request->getPost('product_name');
            $categoryIdsJson    = $this->request->getPost('category_id');
            $subcategoryIdsJson = $this->request->getPost('subcategory_id');
            $brandId            = $this->request->getPost('brand_id');
            $description        = $this->request->getPost('description');
            $publishStatus      = $this->request->getPost('publish_status') ?? 'publish';
            $isPopular          = $this->request->getPost('popular') ?? 0;
            $isDealOfDay        = $this->request->getPost('deal_of_the_day') ?? 0;
            $manufacturer       = $this->request->getPost('manufacturer');
            $madeIn             = $this->request->getPost('made_in');
            $taxIdsJson         = $this->request->getPost('tax_id');
            $taxIncludedInPrice = $this->request->getPost('tax_included_in_price') ?? 1;
            $isReturnable       = $this->request->getPost('is_returnable') ?? 0;
            $returnDays         = $this->request->getPost('return_days') ?? 0;
            $fssaiLicNo         = $this->request->getPost('fssai_lic_no');
            $totalAllowedQty    = $this->request->getPost('total_allowed_quantity');
            $tagsJson           = $this->request->getPost('tags');
            $variationsJson     = $this->request->getPost('variations');

            // Decode JSON arrays
            $categoryIds    = json_decode($categoryIdsJson, true) ?? [];
            $subcategoryIds = json_decode($subcategoryIdsJson, true) ?? [];
            $taxIds         = json_decode($taxIdsJson, true) ?? [];
            $tags           = json_decode($tagsJson, true) ?? [];
            $variations     = json_decode($variationsJson, true) ?? [];

            if (empty($categoryIds)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'At least one category is required',
                ], 422);
            }

            if (empty($variations)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'At least one variation is required',
                ], 422);
            }

            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Handle main image upload
            $mainImage = $this->request->getFile('product_image');
            $imagePath = $product['main_img']; // Keep existing by default

            if ($mainImage && $mainImage->isValid() && !$mainImage->hasMoved()) {
                if ($product['main_img'] && file_exists(FCPATH . $product['main_img'])) {
                    unlink(FCPATH . $product['main_img']);
                }
                $newName   = $mainImage->getRandomName();
                $mainImage->move(FCPATH . 'uploads/products', $newName);
                $imagePath = 'uploads/products/' . $newName;
            }

            // Generate slug if product name changed
            $slug = $product['slug'];
            if ($product['product_name'] !== $productName) {
                $slugBase = preg_replace('/[^A-Za-z0-9-]/', '', strtolower(str_replace(' ', '-', $productName)));
                $slug1    = $slugBase;
                $check    = true;
                $x        = 1;
                while ($check) {
                    $duplicateSlug = $productModel
                        ->where('slug', $slug1)
                        ->where('id !=', $productId)
                        ->countAllResults();
                    if ($duplicateSlug > 0) {
                        $slug1 = $slugBase . $x++;
                    } else {
                        $check = false;
                        $slug  = $slug1;
                    }
                }
            }

            // Update core product row (no category/subcategory/tax columns)
            $productModel->update($productId, [
                'brand_id'               => $brandId,
                'product_name'           => $productName,
                'slug'                   => $slug,
                'main_img'               => $imagePath,
                'description'            => $description,
                'popular'                => $isPopular,
                'deal_of_the_day'        => $isDealOfDay,
                'manufacturer'           => $manufacturer,
                'made_in'                => $madeIn,
                'total_allowed_quantity' => $totalAllowedQty,
                'tax_included_in_price'  => $taxIncludedInPrice,
                'fssai_lic_no'           => $fssaiLicNo,
                'return_days'            => $returnDays,
                'is_returnable'          => $isReturnable,
                'status'                 => $publishStatus === 'publish' ? 1 : 0,
            ]);

            // Replace categories in junction table
            $productCategoriesModel->where('product_id', $productId)->delete();
            foreach ($categoryIds as $catId) {
                $productCategoriesModel->insert([
                    'product_id'  => $productId,
                    'category_id' => (int) $catId,
                ]);
                $alreadyLinked = $sellerCategoriesModel
                    ->where('seller_id', $user['id'])
                    ->where('category_id', (int) $catId)
                    ->countAllResults();
                if (!$alreadyLinked) {
                    $sellerCategoriesModel->insert([
                        'seller_id'   => $user['id'],
                        'category_id' => (int) $catId,
                    ]);
                }
            }

            // Replace subcategories in junction table
            $productSubCategoriesModel->where('product_id', $productId)->delete();
            foreach ($subcategoryIds as $subCatId) {
                $productSubCategoriesModel->insert([
                    'product_id'     => $productId,
                    'subcategory_id' => (int) $subCatId,
                ]);
            }

            // Replace taxes in junction table
            $productTaxesModel->where('product_id', $productId)->delete();
            foreach ($taxIds as $taxId) {
                $productTaxesModel->insert([
                    'product_id' => $productId,
                    'tax_id'     => (int) $taxId,
                ]);
            }

            // Replace variations (delete all, re-insert with images)
            $db->table('product_variants')
                ->where('product_id', $productId)
                ->delete();

            // Also clear variant images (keep main product image row)
            $db->table('product_images')
                ->where('product_id', $productId)
                ->where('product_variant_id IS NOT NULL', null, false)
                ->delete();

            foreach ($variations as $idx => $variation) {
                $isUnlimitedStock = ($variation['stock'] === '' || $variation['stock'] === null) ? 1 : 0;
                $variantId = $productVariantsModel->insert([
                    'product_id'         => $productId,
                    'title'              => $variation['title'],
                    'price'              => $variation['price'],
                    'discounted_price'   => $variation['offerPrice'] ?? 0,
                    'stock'              => $variation['stock'] ?? 0,
                    'is_unlimited_stock' => $isUnlimitedStock,
                    'status'             => 1,
                ]);

                // Handle per-variation image upload
                $variantImageFile = $this->request->getFile('variation_image_' . $idx);
                if ($variantImageFile && $variantImageFile->isValid() && !$variantImageFile->hasMoved()) {
                    $variantImageName = $variantImageFile->getRandomName();
                    $variantImageFile->move(FCPATH . 'uploads/products/variants', $variantImageName);
                    $productImagesModel->insert([
                        'product_id'         => $productId,
                        'product_variant_id' => $variantId,
                        'image'              => 'uploads/products/variants/' . $variantImageName,
                    ]);
                }
            }

            // Handle main image row in product_images
            if ($mainImage && $mainImage->isValid()) {
                // Replace main image row (product_variant_id IS NULL)
                $db->table('product_images')
                    ->where('product_id', $productId)
                    ->where('product_variant_id IS NULL', null, false)
                    ->delete();
                $productImagesModel->insert([
                    'product_id'         => $productId,
                    'product_variant_id' => null,
                    'image'              => $imagePath,
                ]);
            }

            // Replace tags
            $productTagModel->where('product_id', $productId)->delete();
            foreach ($tags as $tagName) {
                $existingTag = $tagModel->where('name', $tagName)->first();
                $tagId       = $existingTag ? $existingTag['id'] : $tagModel->insert([
                    'name'       => $tagName,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $productTagModel->insert([
                    'product_id' => $productId,
                    'tag_id'     => $tagId,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Failed to update product',
                ], 500);
            }

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Product updated successfully',
                'product_id' => $productId,
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Update Product Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sellerDetails()
    {
        $sellerModel = new SellerModel();
        $cityModel = new CityModel();
        $deliverableAreaModel = new DeliverableAreaModel();

        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized seller',
            ], 401);
        }

        try {
            // Get city details
            if ($user['city_id']) {
                $city = $cityModel->where('id', $user['city_id'])->first();
                $user['city'] = $city ? [
                    'id' => $city['id'],
                    'name' => $city['name'],
                    'country' => $city['country'] ?? null,
                ] : null;
            } else {
                $user['city'] = null;
            }

            // Get deliverable area details
            if ($user['deliverable_area_id']) {
                $deliverableArea = $deliverableAreaModel
                    ->where('id', $user['deliverable_area_id'])
                    ->first();
                $user['deliverable_area'] = $deliverableArea ? [
                    'id' => $deliverableArea['id'],
                    'name' => $deliverableArea['deliverable_area_title'],
                    'city_id' => $deliverableArea['city_id'] ?? null,
                ] : null;
            } else {
                $user['deliverable_area'] = null;
            }

            // Format logo URL, fallback to app logo when seller logo is missing
            if (!empty($user['logo'])) {
                $user['logo'] = base_url($user['logo']);
            } elseif (!empty($this->settings['logo'])) {
                $user['logo'] = base_url($this->settings['logo']);
            }

            // Format banner URL
            if ($user['banner']) {
                $user['banner'] = base_url($user['banner']);
            }

            // Format numeric fields
            $user['balance'] = (float)$user['balance'];
            $user['commission'] = (float)$user['commission'];
            $user['latitude'] = (float)$user['latitude'];
            $user['longitude'] = (float)$user['longitude'];

            // Format boolean fields
            $user['status'] = (int)$user['status'];
            $user['require_products_approval'] = (int)$user['require_products_approval'];
            $user['view_customer_details'] = (int)$user['view_customer_details'];
            $user['order_status_permission'] = (int)$user['order_status_permission'];
            $user['is_delete'] = (int)$user['is_delete'];

            // Remove sensitive fields
            unset($user['password']);
            unset($user['reset_link_token']);
            unset($user['reset_token_exp_date']);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Seller Details Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Server error',
            ], 500);
        }
    }

    public function sellerDashboard()
    {
        $sellerModel = new SellerModel();
        $productModel = new ProductModel();
        $productVariantsModel = new ProductVariantsModel();
        $orderModel = new OrderModel();
        $orderProductsModel = new OrderProductModel();
        $sellerWalletTransactionModel = new SellerWalletTransactionModel();

        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized seller',
            ], 401);
        }

        try {
            // Get seller's products
            $sellerProducts = $productModel
                ->where('seller_id', $user['id'])
                ->where('is_delete', 0)
                ->findAll();

            $productIds = array_column($sellerProducts, 'id');

            // Total Products Count
            $totalProducts = count($sellerProducts);

            // Total Orders Count - Using OrderProductsModel since seller_id is there
            $totalOrders = $orderProductsModel
                ->where('seller_id', $user['id'])
                ->groupBy('order_id')
                ->countAllResults();

            // Gross Sales: sum of all order product amounts (excluding cancelled orders)
            $grossSalesResult = $orderProductsModel
                ->select('SUM(CASE WHEN order_products.discounted_price > 0 THEN order_products.discounted_price ELSE order_products.price END) as total')
                ->join('orders', 'orders.id = order_products.order_id')
                ->join('order_status_lists', 'order_status_lists.id = orders.status')
                ->where('order_products.seller_id', $user['id'])
                ->where('LOWER(order_status_lists.status) !=', 'cancelled')
                ->get()
                ->getRowArray();
            $grossSalesAmount = $grossSalesResult['total'] ?? 0;

            // Earnings from wallet transactions
            $earnings = $sellerWalletTransactionModel
                ->where('seller_id', $user['id'])
                ->where('status !=', 3) // Exclude cancelled transactions
                ->selectSum('amount')
                ->first();
            $earningsAmount = $earnings['amount'] ?? 0;

            // Products Sold Out (stock = 0)
            $soldOutProducts = 0;
            $lowStockProducts = 0;

            if (!empty($productIds)) {
                // Get unique products with zero stock
                $soldOutResult = $productVariantsModel
                    ->whereIn('product_id', $productIds)
                    ->where('stock', 0)
                    ->where('is_unlimited_stock', 0)
                    ->where('is_delete', 0)
                    ->select('product_id')
                    ->distinct()
                    ->findAll();
                $soldOutProducts = count($soldOutResult);

                // Get unique products with low stock (1-10 units)
                $lowStockResult = $productVariantsModel
                    ->whereIn('product_id', $productIds)
                    ->where('stock >', 0)
                    ->where('stock <=', 10)
                    ->where('is_unlimited_stock', 0)
                    ->where('is_delete', 0)
                    ->select('product_id')
                    ->distinct()
                    ->findAll();
                $lowStockProducts = count($lowStockResult);
            }

            // Format currency values
            $grossSalesFormatted = number_format($grossSalesAmount, 2);
            $earningsFormatted = number_format($earningsAmount, 2);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    [
                        'id' => '1',
                        'icon' => '💵',
                        'title' => 'Gross Sales',
                        'value' => '₹' . $grossSalesFormatted,
                        'amount' => (float)$grossSalesAmount,
                        'screen' => ''
                    ],
                    [
                        'id' => '2',
                        'icon' => '💰',
                        'title' => 'Earnings',
                        'value' => '₹' . $earningsFormatted,
                        'amount' => (float)$earningsAmount,
                        'screen' => ''
                    ],
                    [
                        'id' => '3',
                        'icon' => '🛒',
                        'title' => 'Total Orders',
                        'value' => (string)$totalOrders,
                        'count' => (int)$totalOrders,
                        'screen' => 'Orders'
                    ],
                    [
                        'id' => '5',
                        'icon' => '📦',
                        'title' => 'Total Products',
                        'value' => (string)$totalProducts,
                        'count' => (int)$totalProducts,
                        'screen' => 'Product'
                    ],
                    [
                        'id' => '7',
                        'icon' => '❌',
                        'title' => 'Products Sold Out',
                        'value' => (string)$soldOutProducts,
                        'count' => (int)$soldOutProducts,
                        'screen' => ''
                    ],
                    [
                        'id' => '8',
                        'icon' => '⚠️',
                        'title' => 'Products Low on Stock',
                        'value' => (string)$lowStockProducts,
                        'count' => (int)$lowStockProducts,
                        'screen' => ''
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Seller Dashboard Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function fetchEarningData()
    {
        date_default_timezone_set($this->timeZone['timezone']);

        $sellerModel = new SellerModel();
        $payload = $this->authorizedToken();

        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->respond([
                'status' => 404,
                'result' => false,
                'message' => 'Seller not found'
            ]);
        }

        // Calculate date ranges
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');

        $currentWeekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $currentWeekEnd = date('Y-m-d 23:59:59', strtotime('sunday this week'));

        $lastWeekStart = date('Y-m-d 00:00:00', strtotime('monday last week'));
        $lastWeekEnd = date('Y-m-d 23:59:59', strtotime('sunday last week'));

        $sellerWalletTransactionModel = new SellerWalletTransactionModel();
        $orderModel = new OrderModel();
        // ==================== TODAY'S DATA ====================

        // Today's Product Earnings
        $todayProductEarningResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'credit')
            ->where('order_id !=', 0)
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->first();
        $todayProductEarning = $todayProductEarningResult['amount'] ?? 0;
        // Today's Total Earnings
        $todayTotalEarning = $todayProductEarning;

        // Today's Product Orders Count
        $todayProductOrdersCount = $orderModel
            ->distinct()
            ->select('orders.id')
            ->join('order_products', 'order_products.order_id = orders.id')
            ->where('order_products.seller_id', $user['id'])
            ->where('orders.created_at >=', $todayStart)
            ->where('orders.created_at <=', $todayEnd)
            ->countAllResults();
        // ==================== CURRENT WEEK DATA ====================

        // Current Week Product Earnings
        $currentWeekProductEarningResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'credit')
            ->where('order_id !=', 0)
            ->where('created_at >=', $currentWeekStart)
            ->where('created_at <=', $currentWeekEnd)
            ->first();
        $currentWeekProductEarning = $currentWeekProductEarningResult['amount'] ?? 0;
        // Current Week Total Earnings
        $currentWeekTotalEarning = $currentWeekProductEarning;

        // Current Week Product Orders Count
        $currentWeekProductOrdersCount = $orderModel
            ->distinct()
            ->select('orders.id')
            ->join('order_products', 'order_products.order_id = orders.id')
            ->where('order_products.seller_id', $user['id'])
            ->where('orders.created_at >=', $currentWeekStart)
            ->where('orders.created_at <=', $currentWeekEnd)
            ->countAllResults();
        // ==================== LAST WEEK DATA ====================

        // Last Week Product Earnings
        $lastWeekProductEarningResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'credit')
            ->where('order_id !=', 0)
            ->where('created_at >=', $lastWeekStart)
            ->where('created_at <=', $lastWeekEnd)
            ->first();
        $lastWeekProductEarning = $lastWeekProductEarningResult['amount'] ?? 0;
        // Last Week Total Earnings
        $lastWeekTotalEarning = $lastWeekProductEarning;

        // Last Week Product Orders Count
        $lastWeekProductOrdersCount = $orderModel
            ->distinct()
            ->select('orders.id')
            ->join('order_products', 'order_products.order_id = orders.id')
            ->where('order_products.seller_id', $user['id'])
            ->where('orders.created_at >=', $lastWeekStart)
            ->where('orders.created_at <=', $lastWeekEnd)
            ->countAllResults();
        // ==================== ALL TIME DATA ====================

        // Total Product Earnings (All Time)
        $totalProductEarningResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'credit')
            ->where('order_id !=', 0)
            ->first();
        $totalProductEarning = $totalProductEarningResult['amount'] ?? 0;
        // Total Credit (All Time)
        $totalCreditResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'credit')
            ->first();
        $totalCredit = $totalCreditResult['amount'] ?? 0;

        // Total Debit (All Time)
        $totalDebitResult = $sellerWalletTransactionModel
            ->selectSum('amount')
            ->where('seller_id', $user['id'])
            ->where('type', 'debit')
            ->first();
        $totalDebit = $totalDebitResult['amount'] ?? 0;

        // Available Balance
        $availableBalance = $user['balance'] ?? 0;

        // Total Product Orders Count (All Time)
        $totalProductOrdersCount = $orderModel
            ->distinct()
            ->select('orders.id')
            ->join('order_products', 'order_products.order_id = orders.id')
            ->where('order_products.seller_id', $user['id'])
            ->countAllResults();
        // ==================== RECENT TRANSACTIONS ====================

        $recentTransactions = $sellerWalletTransactionModel
            ->where('seller_id', $user['id'])
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->findAll();

        $transactionsArray = [];
        foreach ($recentTransactions as $transaction) {
            $transactionsArray[] = [
                'id' => $transaction['id'],
                'order_id' => $transaction['order_id'],
                'transaction_type' => $transaction['order_id'] != 0 ? 'product' : 'other',
                'type' => $transaction['type'], // credit or debit
                'amount' => number_format($transaction['amount'], 2, '.', ''),
                'message' => $transaction['message'] ?? '',
                'remark' => $transaction['remark'] ?? '',
                'status' => $transaction['status'],
                'created_at' => date('j M Y, g:i A', strtotime($transaction['created_at']))
            ];
        }

        // Format dates
        $currentWeekStartFormatted = date('j M', strtotime($currentWeekStart));
        $currentWeekEndFormatted = date('j M', strtotime($currentWeekEnd));
        $lastWeekStartFormatted = date('j M', strtotime($lastWeekStart));
        $lastWeekEndFormatted = date('j M', strtotime($lastWeekEnd));

        return $this->respond([
            'status' => 200,
            'result' => true,
            'data' => [
                // ========== TODAY'S DATA ==========
                'today' => [
                    'date' => date('j M Y'),
                    'product_earning' => number_format($todayProductEarning, 2, '.', ''),
                    'total_earning' => number_format($todayTotalEarning, 2, '.', ''),
                    'product_orders_count' => $todayProductOrdersCount,
                    'total_orders_count' => $todayProductOrdersCount,
                ],

                // ========== CURRENT WEEK DATA ==========
                'current_week' => [
                    'start_date' => $currentWeekStartFormatted,
                    'end_date' => $currentWeekEndFormatted,
                    'product_earning' => number_format($currentWeekProductEarning, 2, '.', ''),
                    'total_earning' => number_format($currentWeekTotalEarning, 2, '.', ''),
                    'product_orders_count' => $currentWeekProductOrdersCount,
                    'total_orders_count' => $currentWeekProductOrdersCount,
                ],

                // ========== LAST WEEK DATA ==========
                'last_week' => [
                    'start_date' => $lastWeekStartFormatted,
                    'end_date' => $lastWeekEndFormatted,
                    'product_earning' => number_format($lastWeekProductEarning, 2, '.', ''),
                    'total_earning' => number_format($lastWeekTotalEarning, 2, '.', ''),
                    'product_orders_count' => $lastWeekProductOrdersCount,
                    'total_orders_count' => $lastWeekProductOrdersCount,
                ],

                // ========== ALL TIME TOTALS ==========
                'all_time' => [
                    'product_earning' => number_format($totalProductEarning, 2, '.', ''),
                    'total_earning' => number_format($totalCredit, 2, '.', ''),
                    'product_orders_count' => $totalProductOrdersCount,
                    'total_orders_count' => $totalProductOrdersCount,
                    'total_credit' => number_format($totalCredit, 2, '.', ''),
                    'total_debit' => number_format($totalDebit, 2, '.', ''),
                    'net_balance' => number_format($totalCredit - $totalDebit, 2, '.', ''),
                ],

                // ========== WALLET BALANCE ==========
                'wallet' => [
                    'available_balance' => number_format($availableBalance, 2, '.', ''),
                    'currency_symbol' => '₹', // You can make this dynamic from settings
                ],

                // ========== RECENT TRANSACTIONS ==========
                'recent_transactions' => $transactionsArray,
                'transactions_count' => count($transactionsArray),
            ]
        ]);
    }

    private function handleFileUpload($fieldName, $uploadPath = 'uploads')
    {
        try {
            $file = $this->request->getFile($fieldName);

            if (!$file || !$file->isValid()) {
                log_message('error', 'File validation failed: ' . $fieldName);
                return null;
            }

            // Validate file size (Max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                log_message('error', 'File too large: ' . $fieldName . ' - Size: ' . $file->getSize());
                return null;
            }

            // Define allowed MIME types
            $allowedMimes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'application/pdf',
            ];

            if (!in_array($file->getMimeType(), $allowedMimes)) {
                log_message('error', 'Invalid MIME type: ' . $file->getMimeType() . ' for ' . $fieldName);
                return null;
            }

            // Generate unique filename
            $newName = $file->getRandomName();

            // Ensure upload directory exists
            $uploadDir = FCPATH . 'uploads/' . $uploadPath;
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            // Move file
            if ($file->move($uploadDir, $newName)) {
                $filePath = $uploadPath . '/' . $newName;
                log_message('info', 'File uploaded successfully: ' . $filePath);
                return $filePath;
            }

            log_message('error', 'Failed to move file: ' . $fieldName);
            return null;
        } catch (\Exception $e) {
            log_message('error', 'File Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteAccount()
    {

        $sellerModel = new SellerModel();

        // Verify seller authorization
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized seller',
            ]);
        }

        $updateStatus = $sellerModel->update($user['id'], ['is_delete' => 1]);

        if ($updateStatus) {
            return $this->respond([
                'status' => 200,
                'result' => 'true',
                'message' => 'Account deleted Successfully'
            ]);
        } else {
            return $this->respond([
                'status' => 500,
                'result' => 'false',
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function fetchProfile()
    {
        try {
            $sellerModel = new SellerModel();
            $sellerCategoriesModel = new SellerCategoriesModel();
            $categoryModel = new CategoryModel();
            $cityModel = new CityModel();
            $deliverableAreaModel = new DeliverableAreaModel();

            // Verify seller authorization
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            $user = $sellerModel
                ->where('status', 1)
                ->where('is_delete', 0)
                ->where('email', $payload['email'])
                ->first();

            if (!$user) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized seller',
                ]);
            }

            // Get city details
            $city = null;
            if ($user['city_id']) {
                $city = $cityModel->select('id, name')
                    ->where('id', $user['city_id'])
                    ->first();
            }

            // Get deliverable area details
            $deliverableArea = null;
            if ($user['deliverable_area_id']) {
                $deliverableArea = $deliverableAreaModel->select('id, deliverable_area_title, city_id')
                    ->where('id', $user['deliverable_area_id'])
                    ->first();
            }

            // Get seller categories (for vendor_type 1 or 3)
            $categories = [];
            $sellerCategories = $sellerCategoriesModel
                ->select('seller_categories.category_id, category.category_name, category.category_img')
                ->join('category', 'category.id = seller_categories.category_id', 'left')
                ->where('seller_categories.seller_id', $user['id'])
                ->findAll();

            foreach ($sellerCategories as $cat) {
                $categories[] = [
                    'id' => $cat['category_id'],
                    'name' => $cat['category_name'],
                    'image' => base_url() . $cat['category_img'],
                ];
            }

            $logoPath = !empty($user['logo']) ? $user['logo'] : ($this->settings['logo'] ?? null);
            $bannerPath = !empty($user['banner']) ? $user['banner'] : null;

            // Prepare response data
            $profileData = [
                // Basic Information
                'id' => $user['id'],
                'name' => $user['name'],
                'store_name' => $user['store_name'],
                'slug' => $user['slug'],
                'email' => $user['email'],
                'mobile' => $user['mobile'],

                // Store Details
                'store_address' => $user['store_address'],
                'map_address' => $user['map_address'],
                'latitude' => $user['latitude'],
                'longitude' => $user['longitude'],
                'logo' => $logoPath ? base_url($logoPath) : null,
                'banner' => $bannerPath ? base_url($bannerPath) : null,

                // Location
                'city_id' => $user['city_id'],
                'deliverable_area_id' => $user['deliverable_area_id'],
                'city' => $city,
                'deliverable_area' => $deliverableArea,

                // Bank Details
                'account_number' => $user['account_number'],
                'bank_ifsc_code' => $user['bank_ifsc_code'],
                'account_name' => $user['account_name'],
                'branch' => $user['branch'],
                'bank_name' => $user['bank_name'],

                // Tax Details
                'pan_number' => $user['pan_number'],
                'tax_number' => $user['tax_number'],
                'tax_name' => $user['tax_name'],

                // Documents
                'national_id_proof' => $user['national_id_proof'],
                'address_proof' => $user['address_proof'],

                // Account Settings
                'balance' => (float)$user['balance'],
                'commission' => (float)$user['commission'],
                'status' => (int)$user['status'],
                'status_reason' => $user['status_reason'],
                'require_products_approval' => (int)$user['require_products_approval'],
                'view_customer_details' => (int)$user['view_customer_details'],
                'order_status_permission' => (int)$user['order_status_permission'],

                // FCM
                'fcm_app_key' => $user['fcm_app_key'],

                // Timestamps
                'registered_at' => $user['registered_at'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],

                // Related Data
                'categories' => $categories,
            ];

            return $this->respond([
                'status' => 'success',
                'message' => 'Profile fetched successfully',
                'data' => $profileData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fetch Profile Exception: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    // updateProfilePic - Only handles logo upload
    public function updateProfilePic()
    {
        try {
            $sellerModel = new SellerModel();

            // Verify seller authorization
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            $user = $sellerModel
                ->where('status', 1)
                ->where('is_delete', 0)
                ->where('email', $payload['email'])
                ->first();

            if (!$user) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized seller',
                ]);
            }

            // Handle logo upload
            $logo = $this->request->getFile('logo');

            if (!$logo || !$logo->isValid()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'No valid logo file provided',
                ]);
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($logo->getMimeType(), $allowedTypes)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Only JPG, JPEG and PNG files are allowed',
                ]);
            }

            // Validate file size (max 5MB)
            if ($logo->getSize() > 5242880) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'File size must not exceed 5MB',
                ]);
            }

            // Delete old logo if exists
            if (!empty($user['logo']) && file_exists(FCPATH . $user['logo'])) {
                unlink(FCPATH . $user['logo']);
            }

            // Upload new logo
            $newName = 'logo_' . $user['id'] . '_' . time() . '.' . $logo->getExtension();
            $logo->move(FCPATH . 'uploads/sellers/logos', $newName);
            $logoPath = 'uploads/sellers/logos/' . $newName;

            // Update database
            $updateData = [
                'logo' => $logoPath,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $sellerModel->update($user['id'], $updateData);

            return $this->respond([
                'status' => 'success',
                'message' => 'Profile picture updated successfully',
                'data' => [
                    'logo' => $logoPath
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Update Profile Pic Exception: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    public function fetchOrderStatusList()
    {
        $orderStatusListsModel = new OrderStatusListsModel();

        $orderStatusList = $orderStatusListsModel->select('id, status')->findAll();

        return $this->respond([
            'status' => 200,
            'result' => 'true',
            'message' => 'Order Status List',
            'data' => $orderStatusList
        ]);
    }

    public function fetchOrderList()
    {
        try {
            $settings = $this->settings;
            $sellerModel = new SellerModel();
            $orderModel = new OrderModel();
            $orderProductModel = new OrderProductModel();

            $dataInput = $this->request->getJSON(true);


            // Verify seller authorization
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            $user = $sellerModel
                ->where('status', 1)
                ->where('is_delete', 0)
                ->where('email', $payload['email'])
                ->first();

            if (!$user) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized seller',
                ]);
            }

            // Get country currency
            $currencySymbol = '₹';

            // Get input filters from request (optional)
            $status = $dataInput['status']; // Filter by status if needed
            $page = $dataInput['page'] ?? 1;
            $limit = $dataInput['limit'] ?? 20;
            $offset = ($page - 1) * $limit;

            // Base query
            $builder = $orderModel->select(
                'orders.id as order_id, 
            orders.user_id, 
            orders.additional_charge, 
            orders.address_id, 
            orders.subtotal, 
            orders.tax, 
            orders.used_wallet_amount, 
            orders.delivery_charge, 
            orders.delivery_tip_amount,
            orders.coupon_amount, 
            orders.order_date, 
            orders.delivery_date, 
            orders.timeslot, 
            orders.delivery_boy_id, 
            orders.transaction_id, 
            orders.status, 
            GROUP_CONCAT(DISTINCT seller.store_name) as seller_names, 
            seller.commission,
            user.name as user_name, 
            user.mobile as user_mobile, 
            address.address, 
            address.city_id, 
            address.area, 
            address.city, 
            address.state, 
            address.pincode, 
            delivery_boy.name as delivery_boy_name, 
            delivery_boy.mobile as delivery_boy_mobile, 
            order_status_lists.status as order_status, 
            order_status_lists.color as order_status_color'
            )
                ->join('order_products', 'order_products.order_id = orders.id', 'left')
                ->join('delivery_boy', 'delivery_boy.id = orders.delivery_boy_id', 'left')
                ->join('order_status_lists', 'order_status_lists.id = orders.status', 'left')
                ->join('user', 'user.id = orders.user_id', 'left')
                ->join('address', 'address.id = orders.address_id', 'left')
                ->join('seller', 'seller.id = order_products.seller_id', 'left')
                ->where('order_products.seller_id', $user['id'])
                ->groupBy('orders.id')
                ->orderBy('orders.order_date', 'DESC');

            // Apply status filter if provided
            if (!empty($status)) {
                $builder->where('orders.status', $status);
            }

            // Get total count for pagination
            $totalOrders = $builder->countAllResults(false);

            // Apply pagination
            $query = $builder->limit($limit, $offset)->get();
            $orders = $query->getResultArray();

            // Get seller settings
            $sellerInfo = $sellerModel->select('view_customer_details')
                ->where('id', $user['id'])
                ->where('is_delete', 0)
                ->where('status', 1)
                ->first();

            // Prepare output
            $orderList = [];
            foreach ($orders as $order) {
                // Calculate subtotal excluding returned items
                $selectSubtotal = $orderProductModel->select('SUM(CASE 
                WHEN order_products.discounted_price = 0 THEN order_products.price * order_products.quantity 
                ELSE order_products.discounted_price * order_products.quantity 
            END) as subtotal')
                    ->join('order_return_request', 'order_return_request.order_products_id = order_products.id AND order_return_request.status IN (2, 4)', 'left')
                    ->where('order_return_request.id IS NULL')
                    ->where('order_products.order_id', $order['order_id'])
                    ->first();

                $subtotal = $selectSubtotal['subtotal'] ?? 0;
                $totalAmount = $subtotal + $order['tax'] + $order['additional_charge'] + $order['delivery_tip_amount']
                    - $order['used_wallet_amount'] + $order['delivery_charge']
                    - $order['coupon_amount'];

                // Prepare address
                $fullAddress = trim($order['address'] . ", " . $order['area'] . ", " .
                    $order['city'] . ", " . $order['state'] . "-" . $order['pincode']);

                // Prepare order data based on seller settings
                $orderData = [
                    'order_id' => $order['order_id'],
                    'order_date' => $order['order_date'],
                    'delivery_date' => $order['delivery_date'],
                    'timeslot' => $order['timeslot'],
                    'status' => $order['order_status'],
                    'status_color' => $order['order_status_color'],
                    'status_id' => $order['status'],
                    'total_amount' => round($totalAmount, 2),
                    'formatted_amount' => $currencySymbol . ' ' . number_format($totalAmount, 2),
                    'currency_symbol' => $currencySymbol,
                    'subtotal' => round($subtotal, 2),
                    'tax' => round($order['tax'], 2),
                    'delivery_charge' => round($order['delivery_charge'], 2),
                    'delivery_tip' => round($order['delivery_tip_amount'], 2),
                    'additional_charge' => round($order['additional_charge'], 2),
                    'coupon_amount' => round($order['coupon_amount'], 2),
                    'wallet_amount' => round($order['used_wallet_amount'], 2),
                    'transaction_id' => $order['transaction_id'],
                ];

                // Add customer details if allowed
                if ($sellerInfo['view_customer_details'] == 1) {
                    $orderData['customer'] = [
                        'user_id' => $order['user_id'],
                        'name' => $order['user_name'],
                        'mobile' => $order['user_mobile'],
                    ];
                    $orderData['address'] = [
                        'full_address' => $fullAddress,
                        'address' => $order['address'],
                        'area' => $order['area'],
                        'city' => $order['city'],
                        'state' => $order['state'],
                        'pincode' => $order['pincode'],
                        'city_id' => $order['city_id'],
                    ];
                }

                // Add delivery boy details if assigned
                if ($order['delivery_boy_id']) {
                    $orderData['delivery_boy'] = [
                        'id' => $order['delivery_boy_id'],
                        'name' => $order['delivery_boy_name'],
                        'mobile' => $order['delivery_boy_mobile'],
                    ];
                } else {
                    $orderData['delivery_boy'] = null;
                }

                $orderList[] = $orderData;
            }

            return $this->respond([
                'status' => 'success',
                'data' => $orderList,
                'pagination' => [
                    'current_page' => (int)$page,
                    'per_page' => (int)$limit,
                    'total' => $totalOrders,
                    'total_pages' => ceil($totalOrders / $limit),
                ],
                'view_customer_details' => (int)$sellerInfo['view_customer_details'],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fetch Order List Exception: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    public function fetchOrderDetails()
    {
        try {
            $settings = $this->settings;
            $sellerModel = new SellerModel();
            $orderModel = new OrderModel();
            $orderProductModel = new OrderProductModel();
            $orderStatusListsModel = new OrderStatusListsModel();
            $deliveryBoyModel = new DeliveryBoyModel();
            $countryModel = new CountryModel();

            // Get order_id from request
            $dataInput = $this->request->getJSON(true);
            $order_id = $dataInput['order_id'] ?? null;

            if (!$order_id) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Order ID is required',
                ]);
            }

            // Verify seller authorization
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            $user = $sellerModel
                ->where('status', 1)
                ->where('is_delete', 0)
                ->where('email', $payload['email'])
                ->first();

            if (!$user) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized seller',
                ]);
            }

            // Get seller info
            $sellerInfo = $sellerModel->select('view_customer_details')
                ->where('id', $user['id'])
                ->where('is_delete', 0)
                ->where('status', 1)
                ->first();

            // Get country currency
            $country = $countryModel->where('is_active', 1)->first();
            $currencySymbol = $country['currency_symbol'] ?? '₹';

            // Get order details
            $orderDetails = $orderModel->select(
                'orders.id as order_id, 
            orders.user_id, 
            orders.address_id, 
            orders.subtotal, 
            orders.tax, 
            orders.used_wallet_amount, 
            orders.additional_charge, 
            orders.delivery_charge, 
            orders.coupon_amount, 
            orders.order_date, 
            orders.delivery_date, 
            orders.timeslot, 
            orders.delivery_boy_id, 
            orders.transaction_id, 
            orders.status, 
            user.name as user_name, 
            user.mobile as user_mobile, 
            user.email as user_email, 
            address.address, 
            address.area, 
            address.city, 
            address.city_id, 
            address.state, 
            address.pincode, 
            delivery_boy.name as delivery_boy_name, 
            delivery_boy.mobile as delivery_boy_mobile, 
            order_status_lists.status as order_status, 
            order_status_lists.color as order_status_color, 
            payment_method.img as payment_method_img, 
            payment_method.title as payment_method_title'
            )
                ->join('delivery_boy', 'delivery_boy.id = orders.delivery_boy_id', 'left')
                ->join('order_status_lists', 'order_status_lists.id = orders.status', 'left')
                ->join('user', 'user.id = orders.user_id', 'left')
                ->join('address', 'address.id = orders.address_id', 'left')
                ->join('payment_method', 'payment_method.id = orders.payment_method_id', 'left')
                ->where('orders.id', $order_id)
                ->first();

            if (!$orderDetails) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Order not found',
                ]);
            }

            // Verify seller has access to this order
            $sellerOrderCheck = $orderProductModel
                ->where('order_id', $order_id)
                ->where('seller_id', $user['id'])
                ->first();

            if (!$sellerOrderCheck) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'You do not have access to this order',
                ]);
            }

            // Get order products for this seller
            $orderProducts = $orderProductModel->select(
                'order_products.id as order_product_id,
            order_products.product_name, 
            order_products.product_variant_name, 
            order_products.quantity, 
            order_products.price, 
            order_products.tax_percentage, 
            order_products.tax_amount, 
            order_products.product_id, 
            order_products.discounted_price, 
            seller.store_name'
            )
                ->join('seller', 'seller.id = order_products.seller_id', 'left')
                ->where('order_products.order_id', $order_id)
                ->where('order_products.seller_id', $user['id'])
                ->findAll();

            // Calculate subtotal for seller's products (excluding returned items)
            $subtotalOfOrder = $orderProductModel->select('SUM(CASE 
            WHEN order_products.discounted_price = 0 THEN order_products.price * order_products.quantity 
            ELSE order_products.discounted_price * order_products.quantity 
        END) as subtotal')
                ->join('order_return_request', 'order_return_request.order_products_id = order_products.id AND order_return_request.status IN (2, 4, 5)', 'left')
                ->where('order_return_request.id IS NULL')
                ->where('order_products.order_id', $order_id)
                ->where('order_products.seller_id', $user['id'])
                ->first();

            $subtotal = $subtotalOfOrder['subtotal'] ?? 0;

            // Calculate total amount
            $totalAmount = $subtotal + $orderDetails['tax'] + $orderDetails['additional_charge']
                - $orderDetails['used_wallet_amount'] + $orderDetails['delivery_charge']
                - $orderDetails['coupon_amount'];

            // Get delivery boys for this city
            $deliveryBoyLists = $deliveryBoyModel->select('id, name')
                ->where('city_id', $orderDetails['city_id'])
                ->where('status', 1)
                ->where('a_status', 1)
                ->where('is_delete', 0)
                ->findAll();

            // Get all status lists
            $statusList = $orderStatusListsModel->findAll();

            // Format order products
            $formattedProducts = [];
            $productModel = new ProductModel();
            foreach ($orderProducts as $product) {
                $productSubtotal = $product['discounted_price'] > 0
                    ? $product['discounted_price'] * $product['quantity']
                    : $product['price'] * $product['quantity'];

                $productData = $productModel->select('main_img')->where('id', $product['product_id'])->first();

                $formattedProducts[] = [
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'product_variant_name' => $product['product_variant_name'],
                    'store_name' => $product['store_name'],
                    'quantity' => (int)$product['quantity'],
                    'price' => $currencySymbol . ' ' . (float)$product['price'],
                    'discounted_price' => $currencySymbol . ' ' . (float)$product['discounted_price'],
                    'final_price' => $product['discounted_price'] > 0
                        ? $currencySymbol . ' ' . $product['discounted_price']
                        : $currencySymbol . ' ' . $product['price'],
                    'tax_percentage' => (float)$product['tax_percentage'],
                    'tax_amount' => (float)$product['tax_amount'],
                    'subtotal' => round($productSubtotal, 2),
                    'formatted_price' => $currencySymbol . ' ' . number_format($product['price'], 2),
                    'formatted_subtotal' => $currencySymbol . ' ' . number_format($productSubtotal, 2),
                    'main_img' => base_url() . $productData['main_img'],
                ];
            }

            // Prepare response data
            $responseData = [
                'order_id' => $orderDetails['order_id'],
                'order_date' => $orderDetails['order_date'],
                'delivery_date' => $orderDetails['delivery_date'],
                'timeslot' => $orderDetails['timeslot'],
                'status' => $orderDetails['order_status'],
                'status_color' => $orderDetails['order_status_color'],
                'status_id' => $orderDetails['status'],
                'transaction_id' => $orderDetails['transaction_id'],
                'payment_method' => [
                    'title' => $orderDetails['payment_method_title'],
                    'image' => $orderDetails['payment_method_img'],
                ],
                'amounts' => [
                    'subtotal' => round($subtotal, 2),
                    'tax' => round($orderDetails['tax'], 2),
                    'delivery_charge' => round($orderDetails['delivery_charge'], 2),
                    'additional_charge' => round($orderDetails['additional_charge'], 2),
                    'coupon_amount' => round($orderDetails['coupon_amount'], 2),
                    'wallet_amount' => round($orderDetails['used_wallet_amount'], 2),
                    'total_amount' => round($totalAmount, 2),
                    'formatted_total' => $currencySymbol . ' ' . number_format($totalAmount, 2),
                    'currency_symbol' => $currencySymbol,
                ],
                'products' => $formattedProducts,
                'delivery_boys' => $deliveryBoyLists,
                'status_list' => $statusList,
            ];

            // Add customer details if allowed
            if ($sellerInfo['view_customer_details'] == 1) {
                $responseData['customer'] = [
                    'user_id' => $orderDetails['user_id'],
                    'name' => $orderDetails['user_name'],
                    'mobile' => $orderDetails['user_mobile'],
                    'email' => $orderDetails['user_email'],
                ];
                $responseData['address'] = [
                    'full_address' => trim($orderDetails['address'] . ", " . $orderDetails['area'] . ", " .
                        $orderDetails['city'] . ", " . $orderDetails['state'] . "-" . $orderDetails['pincode']),
                    'address' => $orderDetails['address'],
                    'area' => $orderDetails['area'],
                    'city' => $orderDetails['city'],
                    'state' => $orderDetails['state'],
                    'pincode' => $orderDetails['pincode'],
                    'city_id' => $orderDetails['city_id'],
                ];
            }

            // Add delivery boy details if assigned
            if ($orderDetails['delivery_boy_id']) {
                $responseData['delivery_boy'] = [
                    'id' => $orderDetails['delivery_boy_id'],
                    'name' => $orderDetails['delivery_boy_name'],
                    'mobile' => $orderDetails['delivery_boy_mobile'],
                ];
            } else {
                $responseData['delivery_boy'] = null;
            }

            return $this->respond([
                'status' => 'success',
                'data' => $responseData,
                'view_customer_details' => (int)$sellerInfo['view_customer_details'],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Fetch Order Details Exception: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateProfile()
    {
        try {
            $sellerModel = new SellerModel();

            // Verify seller authorization
            $payload = $this->authorizedToken();
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            $user = $sellerModel
                ->where('status', 1)
                ->where('is_delete', 0)
                ->where('email', $payload['email'])
                ->first();

            if (!$user) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized seller',
                ]);
            }

            // Get POST data
            $postData = $this->request->getPost();

            // Prepare update data
            $updateData = [];

            // Basic fields that can be updated
            $allowedFields = [
                'name',
                'store_name',
                'email',
                'store_address',
                'map_address',
                'account_number',
                'account_name',
                'bank_name',
                'branch',
                'bank_ifsc_code',
                'pan_number',
                'tax_name',
                'tax_number'
            ];

            foreach ($allowedFields as $field) {
                if (isset($postData[$field])) {
                    $updateData[$field] = $postData[$field];
                }
            }

            // Validate required fields
            if (isset($updateData['email']) && !filter_var($updateData['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid email format',
                ]);
            }

            // Handle banner upload
            // Handle banner upload
            $banner = $this->request->getFile('banner');
            if ($banner && $banner->isValid() && !$banner->hasMoved()) {
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];

                // getMimeType() can be unreliable — use getClientMimeType() as fallback
                $mime = $banner->getMimeType() ?: $banner->getClientMimeType();

                if (!in_array($mime, $allowedMimes)) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Banner: Only JPG, JPEG and PNG files are allowed. Got: ' . $mime,
                    ]);
                }

                if ($banner->getSize() > 5242880) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Banner size must not exceed 5MB',
                    ]);
                }

                // ✅ Ensure directory exists before moving
                $bannerDir = FCPATH . 'uploads/sellers/banners';
                if (!is_dir($bannerDir)) {
                    mkdir($bannerDir, 0755, true);
                }

                // Delete old banner
                if (!empty($user['banner']) && file_exists(FCPATH . $user['banner'])) {
                    unlink(FCPATH . $user['banner']);
                }

                $newName = 'banner_' . $user['id'] . '_' . time() . '.' . $banner->getExtension();
                $banner->move($bannerDir, $newName);
                $updateData['banner'] = 'uploads/sellers/banners/' . $newName;
            }

            // Handle national_id_proof upload
            $nationalId = $this->request->getFile('national_id_proof');
            if ($nationalId && $nationalId->isValid() && !$nationalId->hasMoved()) {
                // Validate
                if (!in_array($nationalId->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'National ID: Only JPG, JPEG and PNG files are allowed',
                    ]);
                }

                if ($nationalId->getSize() > 5242880) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'National ID size must not exceed 5MB',
                    ]);
                }

                // Delete old file
                if (!empty($user['national_id_proof']) && file_exists(FCPATH . $user['national_id_proof'])) {
                    unlink(FCPATH . $user['national_id_proof']);
                }

                $newName = 'national_id_' . $user['id'] . '_' . time() . '.' . $nationalId->getExtension();
                $nationalId->move(FCPATH . 'uploads/sellers/documents', $newName);
                $updateData['national_id_proof'] = 'uploads/sellers/documents/' . $newName;
            }

            // Handle address_proof upload
            $addressProof = $this->request->getFile('address_proof');
            if ($addressProof && $addressProof->isValid() && !$addressProof->hasMoved()) {
                // Validate
                if (!in_array($addressProof->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Address Proof: Only JPG, JPEG and PNG files are allowed',
                    ]);
                }

                if ($addressProof->getSize() > 5242880) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Address Proof size must not exceed 5MB',
                    ]);
                }

                // Delete old file
                if (!empty($user['address_proof']) && file_exists(FCPATH . $user['address_proof'])) {
                    unlink(FCPATH . $user['address_proof']);
                }

                $newName = 'address_proof_' . $user['id'] . '_' . time() . '.' . $addressProof->getExtension();
                $addressProof->move(FCPATH . 'uploads/sellers/documents', $newName);
                $updateData['address_proof'] = 'uploads/sellers/documents/' . $newName;
            }

            if (empty($updateData)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'No data provided for update',
                ]);
            }

            $updateData['updated_at'] = date('Y-m-d H:i:s');

            // Update database
            $sellerModel->update($user['id'], $updateData);

            return $this->respond([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => $updateData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Update Profile Exception: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    public function fetchWeeklyEarningChart()
    {
        date_default_timezone_set($this->timeZone['timezone']);

        $sellerModel = new SellerModel();
        $payload = $this->authorizedToken();

        if ($payload instanceof ResponseInterface) {
            return $payload;
        }

        $user = $sellerModel
            ->where('status', 1)
            ->where('is_delete', 0)
            ->where('email', $payload['email'])
            ->first();

        if (!$user) {
            return $this->respond([
                'status' => 404,
                'result' => false,
                'message' => 'Seller not found'
            ]);
        }

        $db = \Config\Database::connect();
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd   = date('Y-m-d', strtotime('sunday this week'));

        $rows = $db->query(
            "SELECT DATE(created_at) as day, SUM(amount) as total
            FROM seller_wallet_transaction
            WHERE seller_id = ? AND type = 'credit'
            AND DATE(created_at) >= ? AND DATE(created_at) <= ?
            GROUP BY DATE(created_at)",
            [$user['id'], $weekStart, $weekEnd]
        )->getResultArray();

        $earningsByDate = array_column($rows, 'total', 'day');

        $dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weeklyChart = [];

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("monday this week +{$i} days"));
            $weeklyChart[] = [
                'day'     => $dayNames[$i],
                'date'    => $date,
                'earning' => number_format($earningsByDate[$date] ?? 0, 2, '.', ''),
            ];
        }

        return $this->respond([
            'status' => 200,
            'result' => true,
            'data'   => ['weekly_chart' => $weeklyChart]
        ]);
    }

    public function bulkImportProducts()
    {
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) return $payload;

        $sellerModel = new SellerModel();
        $user = $sellerModel->where('email', $payload['email'])
            ->where('status', 1)->where('is_delete', 0)->first();
        if (!$user) return $this->respond(['status' => 404, 'result' => false, 'message' => 'Seller not found']);

        $file = $this->request->getFile('import_file');
        if (!$file || !$file->isValid()) {
            return $this->respond(['status' => 400, 'result' => false, 'message' => 'Invalid file']);
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $csvFile = fopen($file->getTempName(), 'r');

            // Skip the first row if it contains column headers
            fgetcsv($csvFile);

            $productModel = new ProductModel();
            $productVariantsModel = new ProductVariantsModel();
            $productTagModel = new ProductTagModel();
            $tagsModel = new TagsModel();

            $data = [];
            $variantData = [];
            $tagData = [];
            $tagCache = [];

            while (($row = fgetcsv($csvFile, 1000, ',')) !== false) {
                $product = [
                    'product_name' => $row[0],
                    'seller_id' => $user['id'],
                    'brand_id' => $row[1],
                    'category_id' => $row[2],
                    'subcategory_id' => $row[3],
                    'description' => $row[4],
                    'status' => $row[5],
                    'popular' => $row[6],
                    'deal_of_the_day' => $row[7],
                    'tax_id' => $row[8],
                    'manufacturer' => $row[9],
                    'made_in' => $row[10],
                    'is_returnable' => $row[11],
                    'return_days' => $row[12],
                    'total_allowed_quantity' => $row[13],
                    'fssai_lic_no' => $row[14],
                ];

                // Process tags from column 16
                $tags = explode(',', $row[15]); // Comma-separated tags
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);

                    if (!isset($tagCache[$tagName])) {
                        $tagId = $tagsModel->where('name', $tagName)->get()->getRow('id');

                        if (!$tagId) {
                            $tagsModel->insert(['name' => $tagName]);
                            $tagId = $tagsModel->insertID();
                        }

                        $tagCache[$tagName] = $tagId;
                    }

                    $tagData[] = [
                        'product_id' => null, // Placeholder, will update later
                        'tag_id' => $tagCache[$tagName],
                    ];
                }

                // Process variations starting from column 17
                for ($i = 16; $i < count($row); $i += 5) {
                    if (isset($row[$i]) && !empty($row[$i])) {
                        $variantData[] = [
                            'product_id' => null, // Placeholder, will update later
                            'status' => $row[$i + 4],
                            'title' => $row[$i],
                            'price' => $row[$i + 1],
                            'discounted_price' => $row[$i + 2],
                            'stock' => $row[$i + 3],
                        ];
                    }
                }

                $data[] = $product;
            }

            fclose($csvFile);

            if (!empty($data)) {
                foreach ($data as  $product) {
                    $productModel->insert($product);
                    $productId = $productModel->insertID();

                    foreach ($variantData as &$variant) {
                        if ($variant['product_id'] === null) {
                            $variant['product_id'] = $productId;
                        }
                    }

                    foreach ($tagData as &$tag) {
                        if ($tag['product_id'] === null) {
                            $tag['product_id'] = $productId;
                        }
                    }
                }

                if (!empty($variantData)) {
                    $productVariantsModel->insertBatch($variantData);
                }

                if (!empty($tagData)) {
                    $productTagModel->insertBatch($tagData);
                }

                return $this->respond([
                    'status'  => 200,
                    'result'  => true,
                    'message' => 'Bulk import successful',
                    'data'    => ['imported' => count($data)]
                ]);
            } else {
                return $this->respond([
                    'status'  => 400,
                    'result'  => false,
                    'message' => 'No data found in CSV.',
                ]);
            }
        }

        return $this->respond([
            'status'  => 200,
            'result'  => true,
            'message' => 'Bulk import successful',
            'data'    => ['imported' => count($data)]
        ]);
    }

    public function fetchProductSellingReport()
    {
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) return $payload;

        $sellerModel = new SellerModel();
        $user = $sellerModel->where('email', $payload['email'])
            ->where('status', 1)->where('is_delete', 0)->first();
        if (!$user) return $this->respond(['status' => 404, 'result' => false]);

        $startDate = $this->request->getJSON()->start_date ?? date('Y-m-d');
        $endDate   = $this->request->getJSON()->end_date   ?? date('Y-m-d');

        $orderProductModel = new OrderProductModel();
        $results = $orderProductModel
            ->select('product.product_name, product_variants.title AS variant_name,
                    SUM(order_products.quantity) AS total_product_sold,
                    SUM(order_products.price * order_products.quantity) AS total_amount')
            ->join('product_variants', 'order_products.product_variant_id = product_variants.id', 'left')
            ->join('product', 'order_products.product_id = product.id', 'left')
            ->join('orders', 'order_products.order_id = orders.id', 'left')
            ->where('order_products.seller_id', $user['id'])
            ->where('orders.status', 6)
            ->where('DATE(orders.order_date) >=', $startDate)
            ->where('DATE(orders.order_date) <=', $endDate)
            ->groupBy('order_products.product_variant_id')
            ->findAll();

        return $this->respond(['status' => 200, 'result' => true, 'data' => $results]);
    }

    public function fetchSellingReport()
    {
        $payload = $this->authorizedToken();
        if ($payload instanceof ResponseInterface) return $payload;

        $sellerModel = new SellerModel();
        $user = $sellerModel->where('email', $payload['email'])
            ->where('status', 1)->where('is_delete', 0)->first();
        if (!$user) return $this->respond(['status' => 404, 'result' => false]);

        $startDate = $this->request->getJSON()->start_date ?? date('Y-m-d');
        $endDate   = $this->request->getJSON()->end_date   ?? date('Y-m-d');

        $orderProductModel = new OrderProductModel();
        $results = $orderProductModel
            ->select('orders.id AS order_id, order_products.id AS order_item_id,
                    user.name AS user_name, product.product_name,
                    product_variants.title AS variant_name,
                    (order_products.quantity * order_products.price) AS total,
                    orders.created_at AS date')
            ->join('orders', 'order_products.order_id = orders.id', 'left')
            ->join('product', 'order_products.product_id = product.id', 'left')
            ->join('user', 'orders.user_id = user.id', 'left')
            ->join('product_variants', 'order_products.product_variant_id = product_variants.id', 'left')
            ->where('order_products.seller_id', $user['id'])
            ->where('DATE(orders.order_date) >=', $startDate)
            ->where('DATE(orders.order_date) <=', $endDate)
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->respond(['status' => 200, 'result' => true, 'data' => $results]);
    }

    public function fetchPrivacyPolicy()
    {
        $settings = $this->sellerSettings;
        return $this->response->setJSON(['status' => 'success', 'data' => $settings['seller_app_privacy_policy']]);
    }

    public function fetchAboutUs()
    {
        $settings = $this->sellerSettings;
        return $this->response->setJSON(['status' => 'success', 'data' => $settings['seller_app_about']]);
    }

    public function fetchTermsAndConditions()
    {
        $settings = $this->sellerSettings;
        return $this->response->setJSON(['status' => 'success', 'data' => $settings['seller_app_terms_policy']]);
    }
}
