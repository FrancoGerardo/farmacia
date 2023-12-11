<script>
  const base_url = "<?= base_url(); ?>";
</script>
<!-- Essential javascripts for application to work-->
<script src="<?= media(); ?>/js/jquery-3.5.1.min.js"></script>
<script src="<?= media(); ?>/js/popper.min.js"></script>
<script src="<?= media(); ?>/js/bootstrap.min.js"></script>
<script src="<?= media(); ?>/js/main.js"></script>
<script src="<?= media(); ?>/js/fontawesome.js"></script>

<!-- notificacion-->
<script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>

<script type="text/javascript" src="<?= media(); ?>/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/select2.min.js"></script>

<!-- Data table plugin Bottones-->
<script type="text/javascript" src="<?= media(); ?>/js/buttons/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/buttons/jszip.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/buttons/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/buttons/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/buttons/buttons.html5.min.js"></script>
  
<script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>

</body>

</html>