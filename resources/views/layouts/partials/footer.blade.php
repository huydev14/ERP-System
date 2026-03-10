<!-- Main Footer -->
<footer class="main-footer">
    &copy; {{ date('Y') }} <a href="https://github.com/huydev14/workhub-laravel">{{ config('app.name') }}</a>
    by huydev14
    <div class="float-right d-none d-sm-inline-block">
        Enterprise Resource Planning (ERP)
    </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('adminlte/dist/js/pages/dashboard3.js') }}"></script>
</body>

</html>
