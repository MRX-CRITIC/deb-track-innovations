document.querySelectorAll('.useSum').forEach( button =>
    button.addEventListener(
        'click', function () {
            document.getElementById('sum').value =
                this.innerText;
        }
    )
)