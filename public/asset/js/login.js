
function encryptpass(){	
    			
    let pass = document.getElementById('password').value;

    let encryptedstr = CryptoJS.AES.encrypt(JSON.stringify(pass), lK, {format: CryptoJSAesJson}).toString();

    document.getElementById('password').value = encryptedstr;
}

function encryptdata(str) {
    let string = JSON.stringify(str);
    var salt = CryptoJS.lib.WordArray.random(256);
    var iv = CryptoJS.lib.WordArray.random(16);
    const screetkey = "base64:Sy20F3d1yj4jxhukOeFhA2RAZQnjkvY+C9gDdg9pUyI=";
    var key = CryptoJS.PBKDF2(screetkey, salt, {
        hasher: CryptoJS.algo.SHA512,
        keySize: 64 / 8,
        iterations: 999,
    });

    var encrypted = CryptoJS.AES.encrypt(string, key, {
        iv: iv
    });

    var data = {
        ciphertext: CryptoJS.enc.Base64.stringify(encrypted.ciphertext),
        salt: CryptoJS.enc.Hex.stringify(salt),
        iv: CryptoJS.enc.Hex.stringify(iv),
    };

    var data1 = CryptoJS.enc.Hex.stringify(salt)+CryptoJS.enc.Base64.stringify(encrypted.ciphertext)+CryptoJS.enc.Hex.stringify(iv);
    return data1;
}

function decryptdata(str) {
    const screetkey = 'base64:Sy20F3d1yj4jxhukOeFhA2RAZQnjkvY+C9gDdg9pUyI=';
    var obj_json = JSON.parse(str);
  
    if (obj_json != null) {
        var encrypted = obj_json.ciphertext;
        var salt = CryptoJS.enc.Hex.parse(obj_json.salt);
        var iv = CryptoJS.enc.Hex.parse(obj_json.iv);
        var key = CryptoJS.PBKDF2(screetkey, salt, {
            hasher: CryptoJS.algo.SHA512,
            keySize: 64 / 8,
            iterations: 999,
        });
        var decrypted = CryptoJS.AES.decrypt(encrypted, key, {
            iv: iv
        });
        return decrypted.toString(CryptoJS.enc.Utf8);
    }
  }