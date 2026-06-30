let previousDeliveryBoyId = null;
let previousStatus = null;

previousDeliveryBoyId = document.getElementById('delivery_boy_id').value;
previousStatus = document.getElementById('status').value;

function printDiv(divName) {
    var divToPrint = document.getElementById(divName);
    var originalBodyBackgroundColor = document.body.style.backgroundColor;
    var originalHtmlBackgroundColor = document.documentElement.style.backgroundColor;
    document.body.style.backgroundColor = 'white';
    document.documentElement.style.backgroundColor = 'white';
    var printContents = divToPrint.innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    document.body.style.backgroundColor = originalBodyBackgroundColor;
    document.documentElement.style.backgroundColor = originalHtmlBackgroundColor;
}


