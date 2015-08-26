/**
 * Created by Root on 1/8/2015.
 */
(function() {

    'use strict';

    angular
        .module('authApp', ['ui.router', 'satellizer'])
        .config(function($stateProvider, $urlRouterProvider, $authProvider) {

            // Satellizer configuration that specifies which API
            // route the JWT should be retrieved from
            $authProvider.loginUrl = '/api/authenticate';

            // Redirect to the auth state if any other states
            // are requested other than users
            $urlRouterProvider.otherwise('/auth');

            $stateProvider
                .state('auth', {
                    url: '/auth',
                    templateUrl: '/public/views/authView.html',
                    controller: 'AuthController as auth'
                })
                .state('users', {
                    url: '/users',
                    templateUrl: '/public/views/userView.html',
                    controller: 'UserController as user'
                });
        });
})();