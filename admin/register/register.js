function handle_username() {
    var form = document.forms['fRegister']
    var username = form['username'].value
    document.getElementById('error_username').style.visibility = 'visible'
    document.getElementById('btnRegister').disabled = true
    if (username == '') {
        document.getElementById('error_username').innerHTML = '* Bat buoc'
        return false
    } else {
        if (username.length < 6 || username.length > 15) {
            document.getElementById('error_username').innerHTML =
                '* Do dai tu 6 den 15 ki tu'
            return false
        }
        var regex = /^[a-zA-Z][a-zA-Z0-9]{5,14}$/
        if (!regex.test(username)) {
            document.getElementById('error_username').innerHTML =
                '* Ten dang nhap ko hop le'
            return false
        }
    }
    document.getElementById('error_username').style.visibility = 'hidden'
    document.getElementById('btnRegister').disabled = false
    return true
}

function handle_password() {
    var form = document.forms['fRegister']
    var password = form['password'].value
    document.getElementById('error_password').style.visibility = 'visible'
    document.getElementById('btnRegister').disabled = true
    if (password == '') {
        document.getElementById('error_password').innerHTML = '* Bat buoc'
        return false
    } else {
        if (password.length < 6 || password.length > 15) {
            document.getElementById('error_password').innerHTML =
                '* Do dai tu 6 den 15 ki tu'
            return false
        } else {
            var regex = /^[a-zA-Z0-9]{6,15}$/
            if (!regex.test(password)) {
                document.getElementById('error_password').innerHTML =
                    '* Mat khau gom so va ki tu'
                return false
            } else {
                var count = 0
                for (var i in password) {
                    var c = password[i]
                    count += '0' <= c && c <= '9'
                }
                if (count == password.length || count == 0) {
                    document.getElementById('error_password').innerHTML =
                        '* Mat khau gom so va ki tu'
                    return false
                }
            }
        }
    }
    document.getElementById('error_password').style.visibility = 'hidden'
    document.getElementById('btnRegister').disabled = false
    return true
}

function handle_repassword() {
    var form = document.forms['fRegister']
    var password = form['password'].value
    var repassword = form['repassword'].value
    document.getElementById('error_repassword').style.visibility = 'visible'
    document.getElementById('btnRegister').disabled = true
    if (password == '') {
        document.getElementById('error_repassword').innerHTML = '* Bat buoc'
        return false
    } else {
        if (password != repassword) {
            document.getElementById('error_repassword').innerHTML =
                '* Mat khau khong khop'
            return false
        }
    }
    document.getElementById('error_repassword').style.visibility = 'hidden'
    document.getElementById('btnRegister').disabled = false
    return true
}
