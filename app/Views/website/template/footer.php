<footer class="bg-white dark:bg-gray-900 p-4 relative bottom-0 w-full mt-4">
	<div class="container max-w-7xl mx-auto">
		<div class="flex flex-wrap md:gap-4 lg:gap-0 py-4 mb-6">
			<div class="w-full md:w-full lg:w-1/3 flex flex-col gap-4 mb-6">
				<a class="" href="/">
					<img src="<?= base_url($settings['logo']) ?>" class="rounded-lg w-8" alt="<?= $settings['business_name'] ?>" />
				</a>
				<p class="dark:text-gray-300"><?= $settings['short_description'] ?></p>

				<ul class="flex items-center text-sm gap-4 mt-3">
					<?php $socialLinks = json_decode($settings['social_link'], true); ?>
					<?php foreach ($socialLinks as $social): ?>
						<?php if ($social['status'] == 1): ?>
							<li>
								<a href="<?= htmlspecialchars($social['link']) ?>" target="_blank" class="dark:text-gray-300 dark:hover:text-green-400">
									<i class="<?= htmlspecialchars($social['icon']) ?> text-lg"></i>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<div class="flex gap-3">
					<?php if (isset($settings['app_url_android']) && $settings['app_url_android'] != null && $settings['app_url_android'] != ''): ?>
						<a target="_blank" href="<?= htmlspecialchars($settings['app_url_android']); ?>">
							<img src="<?= base_url() . 'assets/dist/img/googleplay-btn.svg' ?>" alt="" class="h-8 rounded-lg dark:brightness-90" />
						</a>
					<?php endif; ?>
					<?php if (isset($settings['app_url_ios']) && $settings['app_url_ios'] != null && $settings['app_url_ios'] != ''): ?>
						<a target="_blank" href="<?= htmlspecialchars($settings['app_url_ios']); ?>">
							<img src="<?= base_url() . 'assets/dist/img/appstore-btn.svg' ?>" alt="" class="h-8 rounded-lg dark:brightness-90" />
						</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="w-full md:w-full lg:w-2/3">
				<div class="flex flex-wrap">
					<div class="w-1/2 sm:w-1/2 md:w-1/3 flex flex-col gap-4 mb-6">
						<h6 class="text-[22px] font-semibold capitalize dark:text-white"><?php echo lang('website.support'); ?></h6>
						<ul class="flex flex-col gap-2">
							<li><a href="/about-us" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.about_us'); ?></a>
							<li><a href="/contact-us" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.contact_us'); ?></a>
							<li><a href="/faq" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.faq'); ?></a>
							<li><a href="/seller/login" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border-2 border-green-700 text-green-700 hover:bg-green-700 hover:text-white hover:border-green-700 dark:border-green-400 dark:text-green-400 dark:hover:bg-green-400 dark:hover:text-white font-medium text-sm shadow-sm transition-all duration-200"><?php echo lang('website.become_a_seller'); ?><i class="fi fi-tr-plus ml-2"></i></a>
						</ul>
					</div>
					<div class="w-1/2 sm:w-1/2 md:w-1/3 flex flex-col gap-4 mb-6">
						<h6 class="text-[22px] font-semibold capitalize dark:text-white"><?php echo lang('website.legal'); ?></h6>
						<ul class="flex flex-col gap-2">
							<li><a href="/privacy-policy" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.privacy_policy'); ?></a>
							<li><a href="/terms-condition" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.terms_condition'); ?></a>
							<li><a href="/refund-policy" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300"><?php echo lang('website.refund_policy'); ?></a>
						</ul>
					</div>
					<div class="sm:w-1/2 md:w-1/3 flex flex-col gap-4">
						<h6 class="text-[22px] font-semibold capitalize dark:text-white"><?php echo lang('website.conatct'); ?></h6>
						<ul class="flex flex-col gap-2">
							<?php if (isset($settings['phone']) && $settings['phone'] != null && $settings['phone'] != ''): ?>
								<li>
									<a href="tel:<?= htmlspecialchars($settings['phone']); ?>" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300">
										<i class="fi fi-rr-phone-call"></i> <?= htmlspecialchars($settings['phone']); ?>
									</a>
								</li>
							<?php endif; ?>
							<?php if (isset($settings['email']) && $settings['email'] != null && $settings['email'] != ''): ?>
								<li>
									<a href="mailto:<?= htmlspecialchars($settings['email']); ?>" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300">
										<i class="fi fi-rr-envelope"></i> <?= htmlspecialchars($settings['email']); ?>
									</a>
								</li>
							<?php endif; ?>
							<?php
							$location = json_decode($settings['address'], true);
							if (
								isset($location['address']) && $location['address'] != ''
								&& isset($location['latitude']) && $location['latitude'] != ''
								&& isset($location['longitude']) && $location['longitude'] != ''
							):
								$googleMapsLink = "https://www.google.com/maps?q={$location['latitude']},{$location['longitude']}";
							?>
								<li>
									<a href="<?= htmlspecialchars($googleMapsLink); ?>" target="_blank" class="inline-block hover:text-green-600 dark:hover:text-green-400 text-sm font-medium dark:text-gray-300">
										<i class="fi fi-rr-marker"></i> <?= htmlspecialchars($location['address']); ?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php if (isset($settings['footer_text']) && $settings['footer_text'] != null && $settings['footer_text'] != ''): ?>
			<div class="border-t py-4 border-gray-300 dark:border-gray-700">
				<div class="gap-y-4 flex flex-wrap items-center justify-center text-sm font-semibold capitalize dark:text-gray-300">
					<?= htmlspecialchars($settings['footer_text']); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</footer>
<script src="<?= base_url('/assets/page-script/website/search.js') ?>"></script>