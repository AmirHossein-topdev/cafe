<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>

    <!-- // تعریف نگاشت اعداد فارسی به انگلیسی -->
<script>
    function convertPersianToEnglish(input) {
        const persianToEnglish = {
            '۰': '0',
            '۱': '1',
            '۲': '2',
            '۳': '3',
            '۴': '4',
            '۵': '5',
            '۶': '6',
            '۷': '7',
            '۸': '8',
            '۹': '9'
        };

        // مقدار فعلی ورودی را دریافت کنید
        let value = input.value;

        // تبدیل اعداد فارسی به انگلیسی
        value = value.replace(/[۰-۹]/g, (match) => persianToEnglish[match]);


        // مقدار تبدیل شده را به ورودی اختصاص دهید
        input.value = formattedValue;
    }
</script>

<!-- total-price -->
<script>
    // Function to calculate and show the total price
    document.addEventListener("DOMContentLoaded", function () {
        let totalPrice = 0;

        // Loop through each cart and sum the total price from the table
        document.querySelectorAll('.total-price').forEach(function (element) {
            let tablePrice = parseFloat(element.textContent.replace(/[^0-9.-]+/g, "")); // Clean the text to get the number
            totalPrice += tablePrice;
        });

        // Display the total price in the #total-price span
        document.getElementById('total-price').textContent = new Intl.NumberFormat().format(totalPrice) + " تومان";
    });
</script>

<!-- chage "" to -  -->
<script>
    // Function to replace spaces with hyphens for the 'alt' field only
    function convertSpaceToHyphen(inputField) {
        inputField.value = inputField.value.replace(/\s+/g, '-');
    }
</script>

<!-- play-sound -->




</body>

</html>