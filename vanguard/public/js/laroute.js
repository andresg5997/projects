(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/estados","name":"api.estados","action":"App\Http\Controllers\EstadosController@get"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/correos","name":"api.correos","action":"App\Http\Controllers\CorreosController@get"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/estado\/{estado}","name":"api.estado","action":"App\Http\Controllers\EstadosController@getEstado"},{"host":null,"methods":["POST"],"uri":"api\/marcas\/{marca}\/subirArchivos","name":null,"action":"App\Http\Controllers\MarcasController@subirArchivos"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/usuarios\/{id}\/marcas","name":"api.marcas","action":"App\Http\Controllers\MarcasController@get"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/marcas\/{id}","name":"api.marca","action":"App\Http\Controllers\MarcasController@getMarca"},{"host":null,"methods":["PUT"],"uri":"api\/marcas\/{id}","name":"api.marca.update","action":"App\Http\Controllers\MarcasController@update"},{"host":null,"methods":["PUT"],"uri":"api\/marcas\/{marca}\/update","name":"api.marca.campo.update","action":"App\Http\Controllers\MarcasController@updateValor"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/paginate\/marcas","name":null,"action":"App\Http\Controllers\MarcasController@paginate"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/search\/marcas","name":null,"action":"App\Http\Controllers\MarcasController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/procesos","name":"api.procesos","action":"App\Http\Controllers\ProcesosController@get"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/procesos\/{id}","name":"api.proceso","action":"App\Http\Controllers\ProcesosController@getProceso"},{"host":null,"methods":["POST"],"uri":"api\/procesos","name":"api.procesos.store","action":"App\Http\Controllers\ProcesosController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/usuarios\/{id}\/tareas","name":"api.usuarios.tareas","action":"App\Http\Controllers\TareasController@get"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/tareas\/{id}","name":"api.tarea","action":"App\Http\Controllers\TareasController@getTarea"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/transacciones","name":"api.transacciones","action":"App\Http\Controllers\TransaccionesController@get"},{"host":null,"methods":["POST"],"uri":"api\/transacciones","name":null,"action":"App\Http\Controllers\TransaccionesController@store"},{"host":null,"methods":["PUT"],"uri":"api\/usuarios\/{id}","name":null,"action":"App\Http\Controllers\UsersController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/usuarios\/{id}","name":null,"action":"App\Http\Controllers\UsersController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/changePassword","name":"api.changePassword","action":"App\Http\Controllers\UsersController@changePassword"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/usuarios\/{id}\/usuarios","name":"api.usuario.usuarios","action":"App\Http\Controllers\UsersController@get"},{"host":null,"methods":["POST"],"uri":"api\/changeType","name":"api.changeType","action":"App\Http\Controllers\UsersController@changeType"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/usuarios","name":"api.usuarios","action":"App\Http\Controllers\UsersController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/usuarios\/{id}","name":"api.usuario","action":"App\Http\Controllers\UsersController@getUsuario"},{"host":null,"methods":["PUT"],"uri":"api\/updateAvatar","name":"api.updateAvatar","action":"App\Http\Controllers\UsersController@updateAvatar"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"home","action":"App\Http\Controllers\HomeController@dashboard"},{"host":null,"methods":["GET","HEAD"],"uri":"config\/dashboard","name":"home.config","action":"App\Http\Controllers\HomeController@configDashboard"},{"host":null,"methods":["GET","HEAD"],"uri":"config\/adicionales","name":"datosAdicionales.config","action":"App\Http\Controllers\HomeController@configDatosAdicionales"},{"host":null,"methods":["GET","HEAD"],"uri":"config\/correos","name":"correos.config","action":"App\Http\Controllers\CorreosController@configCorreos"},{"host":null,"methods":["POST"],"uri":"marcas\/datosAdicionales","name":"marcas.datos.config.store","action":"App\Http\Controllers\MarcasController@storeDatosAdicionales"},{"host":null,"methods":["GET","HEAD"],"uri":"marcas\/datosAdicionales","name":"marcas.datos.config","action":"App\Http\Controllers\MarcasController@getDatosAdicionales"},{"host":null,"methods":["GET","HEAD"],"uri":"dashboard","name":"dashboard.get","action":"App\Http\Controllers\HomeController@getDashboards"},{"host":null,"methods":["POST"],"uri":"dashboard","name":"dashboard.store","action":"App\Http\Controllers\HomeController@storeDashboard"},{"host":null,"methods":["DELETE"],"uri":"dashboard\/{dashboard}","name":"dashboard.destroy","action":"App\Http\Controllers\HomeController@destroyDashboard"},{"host":null,"methods":["PUT"],"uri":"dashboard\/{dashboard}","name":"dashboard.update","action":"App\Http\Controllers\HomeController@updateDashboard"},{"host":null,"methods":["GET","HEAD"],"uri":"correos","name":"correos.index","action":"App\Http\Controllers\CorreosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"correos\/create","name":"correos.create","action":"App\Http\Controllers\CorreosController@create"},{"host":null,"methods":["POST"],"uri":"correos","name":"correos.store","action":"App\Http\Controllers\CorreosController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"correos\/{correo}","name":"correos.show","action":"App\Http\Controllers\CorreosController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"correos\/{correo}\/edit","name":"correos.edit","action":"App\Http\Controllers\CorreosController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"correos\/{correo}","name":"correos.update","action":"App\Http\Controllers\CorreosController@update"},{"host":null,"methods":["DELETE"],"uri":"correos\/{correo}","name":"correos.destroy","action":"App\Http\Controllers\CorreosController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"marcas","name":"marcas.index","action":"App\Http\Controllers\MarcasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"marcas\/create","name":"marcas.create","action":"App\Http\Controllers\MarcasController@create"},{"host":null,"methods":["POST"],"uri":"marcas","name":"marcas.store","action":"App\Http\Controllers\MarcasController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"marcas\/{marca}","name":"marcas.show","action":"App\Http\Controllers\MarcasController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"marcas\/{marca}\/edit","name":"marcas.edit","action":"App\Http\Controllers\MarcasController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"marcas\/{marca}","name":"marcas.update","action":"App\Http\Controllers\MarcasController@update"},{"host":null,"methods":["DELETE"],"uri":"marcas\/{marca}","name":"marcas.destroy","action":"App\Http\Controllers\MarcasController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"usuarios","name":"usuarios.index","action":"App\Http\Controllers\UsersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"usuarios\/create","name":"usuarios.create","action":"App\Http\Controllers\UsersController@create"},{"host":null,"methods":["POST"],"uri":"usuarios","name":"usuarios.store","action":"App\Http\Controllers\UsersController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"usuarios\/{usuario}","name":"usuarios.show","action":"App\Http\Controllers\UsersController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"usuarios\/{usuario}\/edit","name":"usuarios.edit","action":"App\Http\Controllers\UsersController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"usuarios\/{usuario}","name":"usuarios.update","action":"App\Http\Controllers\UsersController@update"},{"host":null,"methods":["DELETE"],"uri":"usuarios\/{usuario}","name":"usuarios.destroy","action":"App\Http\Controllers\UsersController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"tareas","name":"tareas.index","action":"App\Http\Controllers\TareasController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"tareas\/create","name":"tareas.create","action":"App\Http\Controllers\TareasController@create"},{"host":null,"methods":["POST"],"uri":"tareas","name":"tareas.store","action":"App\Http\Controllers\TareasController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"tareas\/{tarea}","name":"tareas.show","action":"App\Http\Controllers\TareasController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"tareas\/{tarea}\/edit","name":"tareas.edit","action":"App\Http\Controllers\TareasController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"tareas\/{tarea}","name":"tareas.update","action":"App\Http\Controllers\TareasController@update"},{"host":null,"methods":["DELETE"],"uri":"tareas\/{tarea}","name":"tareas.destroy","action":"App\Http\Controllers\TareasController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"oposiciones","name":"oposiciones.index","action":"App\Http\Controllers\OposicionesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"oposiciones\/create","name":"oposiciones.create","action":"App\Http\Controllers\OposicionesController@create"},{"host":null,"methods":["POST"],"uri":"oposiciones","name":"oposiciones.store","action":"App\Http\Controllers\OposicionesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"oposiciones\/{oposicione}","name":"oposiciones.show","action":"App\Http\Controllers\OposicionesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"oposiciones\/{oposicione}\/edit","name":"oposiciones.edit","action":"App\Http\Controllers\OposicionesController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"oposiciones\/{oposicione}","name":"oposiciones.update","action":"App\Http\Controllers\OposicionesController@update"},{"host":null,"methods":["DELETE"],"uri":"oposiciones\/{oposicione}","name":"oposiciones.destroy","action":"App\Http\Controllers\OposicionesController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"transacciones","name":"transacciones.index","action":"App\Http\Controllers\TransaccionesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"transacciones\/create","name":"transacciones.create","action":"App\Http\Controllers\TransaccionesController@create"},{"host":null,"methods":["POST"],"uri":"transacciones","name":"transacciones.store","action":"App\Http\Controllers\TransaccionesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"transacciones\/{transaccione}","name":"transacciones.show","action":"App\Http\Controllers\TransaccionesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"transacciones\/{transaccione}\/edit","name":"transacciones.edit","action":"App\Http\Controllers\TransaccionesController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"transacciones\/{transaccione}","name":"transacciones.update","action":"App\Http\Controllers\TransaccionesController@update"},{"host":null,"methods":["DELETE"],"uri":"transacciones\/{transaccione}","name":"transacciones.destroy","action":"App\Http\Controllers\TransaccionesController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"procesos","name":"procesos.index","action":"App\Http\Controllers\ProcesosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"procesos\/create","name":"procesos.create","action":"App\Http\Controllers\ProcesosController@create"},{"host":null,"methods":["POST"],"uri":"procesos","name":"procesos.store","action":"App\Http\Controllers\ProcesosController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"procesos\/{proceso}","name":"procesos.show","action":"App\Http\Controllers\ProcesosController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"procesos\/{proceso}\/edit","name":"procesos.edit","action":"App\Http\Controllers\ProcesosController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"procesos\/{proceso}","name":"procesos.update","action":"App\Http\Controllers\ProcesosController@update"},{"host":null,"methods":["DELETE"],"uri":"procesos\/{proceso}","name":"procesos.destroy","action":"App\Http\Controllers\ProcesosController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"estados","name":"estados.index","action":"App\Http\Controllers\EstadosController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"estados\/create","name":"estados.create","action":"App\Http\Controllers\EstadosController@create"},{"host":null,"methods":["POST"],"uri":"estados","name":"estados.store","action":"App\Http\Controllers\EstadosController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"estados\/{estado}","name":"estados.show","action":"App\Http\Controllers\EstadosController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"estados\/{estado}\/edit","name":"estados.edit","action":"App\Http\Controllers\EstadosController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"estados\/{estado}","name":"estados.update","action":"App\Http\Controllers\EstadosController@update"},{"host":null,"methods":["DELETE"],"uri":"estados\/{estado}","name":"estados.destroy","action":"App\Http\Controllers\EstadosController@destroy"},{"host":null,"methods":["POST"],"uri":"procesos\/{proceso}\/cliente\/{marca}","name":"procesos.init","action":"App\Http\Controllers\ProcesosController@procesoInit"},{"host":null,"methods":["GET","HEAD"],"uri":"transacciones\/create\/{tareaId}","name":null,"action":"App\Http\Controllers\TransaccionesController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"estados\/create\/procesos\/{proceso}","name":null,"action":"App\Http\Controllers\ProcesosController@createEstado"},{"host":null,"methods":["GET","HEAD"],"uri":"estados\/{estado}\/edit\/procesos\/{proceso}","name":null,"action":"App\Http\Controllers\ProcesosController@editEstado"},{"host":null,"methods":["GET","HEAD"],"uri":"estado\/{estado}","name":"estado.get","action":"App\Http\Controllers\EstadosController@getEstado"},{"host":null,"methods":["GET","HEAD"],"uri":"notificacion\/readAll","name":"notifications.readAll","action":"App\Http\Controllers\NotificationsController@readAll"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"register","name":"register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["POST"],"uri":"register","name":null,"action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset","name":"password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"password\/email","name":"password.email","action":"App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":"password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset","name":null,"action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"notificacion\/{id}","name":"notifications.show","action":"App\Http\Controllers\NotificationsController@markAsRead"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

