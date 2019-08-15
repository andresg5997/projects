<footer class="page-footer blue darken-4">
  <div class="container">
    <div class="row">
      <div class="col l6 s12">
        <h5 class="white-text">NetWork Latino</h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Incidunt quia, totam. Iste voluptates esse hic molestias voluptate tempora deserunt repudiandae, nesciunt aut atque sit quisquam deleniti consectetur autem. Veniam, hic.</p>
      </div>
      <div class="col l4 offset-l2 s12">
        <h5 class="white-text">Links</h5>
        <ul>
          <li><a class="grey-text text-lighten-3" href="{{ route('home') }}">Inicio</a></li>
          <li><a class="grey-text text-lighten-3" href="{{ route('marcas.index') }}">Marcas</a></li>
          <li><a class="grey-text text-lighten-3" href="{{ route('oposiciones.index') }}">Oposición</a></li>
          <li><a class="grey-text text-lighten-3" href="{{ route('usuarios.show', Auth::id()) }}">Perfil</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-copyright">
    <div class="container">
    &copy; 2017 Copyright Text
    </div>
  </div>
  </footer>
  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/materialize.min.js') }}"></script>
  <script src="{{ asset('vendor/toastr/toastr.min.js') }}" type="text/javascript"r></script>
  <script src="{{ asset('js/laroute.js') }}"></script>
  @yield('scripts')
  <script>
      $(document).ready(function(){
            $(".button-collapse").sideNav();
            $('.modal').modal();
            $('select').material_select();
            $('.datepicker').pickadate({
              selectMonths: true, // Creates a dropdown to control month
              selectYears: 100, // Creates a dropdown of 100 years to control year,
              today: 'Hoy',
              clear: '',
              close: 'Ok',
              closeOnSelect: false, // Close upon selecting a date,
              format: 'yyyy-mm-dd'
            });
      });
  </script>
</body>
</html>
