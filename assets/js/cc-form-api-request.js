jQuery(document).ready(function($) {
    $('#cc-form-trigger').on("click", function() {
        convertCurrency();
    });

    function convertCurrency() {
        const plnAmount = document.getElementById('pln-currency');
        const eurAmount = document.getElementById('eur-currency');

        const value = plnAmount.value.trim();

        if (value === '') {
            alert('Wartość nie może być pusta.');
            return;
        }

        const numberValue = parseFloat(value);

        if (Number.isNaN(numberValue) || numberValue < 0) {
            alert('Wartość musi być liczbą dodatnią.');
            return;
        }

        $.ajax({
            url: wp_ajax_data.ajax_url,
            type: 'POST',
            data: {
                action: wp_ajax_data.action,
                nonce: wp_ajax_data.nonce,
                value: numberValue
            },
            success: function(response) {
                if(response.success) {
                    const valInEuro = response.value_in_eur;
                    if(valInEuro){
                        eurAmount.value = valInEuro.toFixed(2); 
                    }
                }else{
                    alert('Błąd: ' + response.data.message);

                }
            },
            error: function(xhr, status, error) {
                alert('Wystąpił błąd');
                
            }
        });
    }
});
