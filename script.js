
    $(document).ready(function() {
        $("form").on("submit", function(event) {
            event.preventDefault();
            $.post("login.php", $(this).serialize(), function(data) {
                const parsedData = JSON.parse(data);
                if (parsedData.errorType === 'InvalidCredentials') {
                    alert(parsedData.errorMessage);
                }
            });
        });
    });
