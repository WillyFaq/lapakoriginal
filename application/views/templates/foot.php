
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script> 

    <script type="text/javascript">
        $(document).ready(function(){
            if ($(window).width() < 768) {
                var table = $(".dataTable").DataTable({
                    "scrollX": true
                });
            }else{
                var table = $(".dataTable").DataTable();
            }
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(function(){
                $(".alert").hide(500);
            }, 3000);

            var url = document.URL;
            var segments = url.split('/');
            if(segments[4]!=''){
                var id = segments[4];
                $("#"+id).addClass("active");
            }else{
                $("#home").addClass("active");
            }
        });
    </script>
    
</body>

</html>