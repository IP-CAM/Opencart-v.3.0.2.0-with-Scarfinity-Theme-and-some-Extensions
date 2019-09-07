console.log('account.js', 'loaded');

var account = {
    loading: false,
    login: function() {
        var account = this;

        if(!this.loading) {
            $.ajax({
                url: 'index.php?route=account/login/login',
                data: {
                    email: '',
                    telephone: '',
                    password: ''
                },
                type: 'post',
                complete: function () {
                    account.loading = false;
                },
                success: function (json) {
                    console.log(json);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    },
    telephone: function(telephone) {
        var telephones = {
            russia: /^((\+7|7|8)+([0-9]){10})$/gm
        }

        return telephones.russia.test(telephone);
    }
}