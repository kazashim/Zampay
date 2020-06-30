let sanitizedPhone = function (phone) {
    phone = phone.trim();
    if (phone.charAt(0) === '+'){
        phone = phone.substring(4, phone.length);
        return '0' + phone.trim();
    }
    return phone;
};

let syncInputValues = function(from, to){
    to.value = sanitizedPhone(from.value);
};

let billPhone;
let momoPhone;

//initialize payment phone
window.onload = function(){
    billPhone = document.getElementById('billing_phone');
    momoPhone = document.getElementById('momopay_phone');

    if (billPhone !== null && momoPhone !== null){
        syncInputValues(billPhone, momoPhone);
    }
};