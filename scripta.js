
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDTNiDS-yiAIKdXeW8_3K5K1QMQswieYFE",
  authDomain: "ayur-ai-otp-verify.firebaseapp.com",
  projectId: "ayur-ai-otp-verify",
  storageBucket: "ayur-ai-otp-verify.firebasestorage.app",
  messagingSenderId: "130788058329",
  appId: "1:130788058329:web:9ac15465ee5e93d8748ac8",
  measurementId: "G-K1WSPHNH4C"
};
// initializing firebase SDK
firebase.initializeApp(firebaseConfig);

// render recaptcha verifier
render();
function render() {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
    recaptchaVerifier.render();
}

// function for send OTP
function sendOTP() {
    var number = document.getElementById('number').value;
    firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;
        document.querySelector('.number-input').style.display = 'none';
        document.querySelector('.verification').style.display = '';
    }).catch(function (error) {
        // error in sending OTP
        alert(error.message);
    });
}

// function for OTP verify
function verifyCode() {
    var code = document.getElementById('verificationCode').value;
    coderesult.confirm(code).then(function () {
        document.querySelector('.verification').style.display = 'none';
        document.querySelector('.result').style.display = '';
        document.querySelector('.correct').style.display = '';
        console.log('OTP Verified');
    }).catch(function () {
        document.querySelector('.verification').style.display = 'none';
        document.querySelector('.result').style.display = '';
        document.querySelector('.incorrect').style.display = '';
        console.log('OTP Not correct');
    })
}
