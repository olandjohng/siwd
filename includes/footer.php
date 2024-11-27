</main>

<script src="other/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="other/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="other/simplebar/dist/simblebar.min.js"></script> 
<script src="assets/js/volt.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        sidebarToggle.addEventListener('click', function() {
            // Toggle the 'contracted' class on the sidebarMenu
            sidebarMenu.classList.toggle('contracted');
        });
    });
</script>
</body>

</html>