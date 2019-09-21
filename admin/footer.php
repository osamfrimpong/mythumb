 <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <!-- Anything you want -->
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?= date("Y"); ?> <a href="#">MyThumb</a>.</strong> All rights reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- <script src="dist/css/moment-with-locales.js"></script>
 --><script src="bower_components/moment/moment.js"></script>

<!-- bootstrap datepicker -->
<script src="dist/css/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
  $(function () {
  	//Date picker
    $('#start_time').datetimepicker({
      sideBySide: true,
      format: "Y-M-D H:m:s",

    })

    $('#end_time').datetimepicker({
      sideBySide: true,
      format: "Y-M-D H:m:s",
    })

   $('#voters_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'scrollX'     : true,
      'pageLength'  : 100
    })
})
</script>

 <?php if(!empty($error_batch) || !empty($success_batch))
                      {
                        echo '<div class="modal fade" tabindex="-1" role="dialog" id="addBatchModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Batch Voters!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="overflow:auto;height:400px;">';

                    echo $error_batch.$success_batch;

                echo '    </p>
                </div>
                <div class="modal-footer">
                    <a href="voters" class="btn btn-success" data-dismiss="modal">OK</a>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">'.
"$('#addBatchModal').modal('show');
</script>

    ";
                      }
                      
               ?>

</body>
</html>