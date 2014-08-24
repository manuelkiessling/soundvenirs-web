/**
 * Setup of main AngularJS application
 *
 * @see SoundLocationsController
 */

var soundvenirsWebapp = angular.module('soundvenirsWebapp', ['ngRoute', 'restangular', 'google-maps']);

var scripts = document.getElementsByTagName("script")
var currentScriptPath = scripts[scripts.length-1].src;

soundvenirsWebapp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: currentScriptPath.substring(0, currentScriptPath.lastIndexOf('/') + 1) + 'partials/soundLocations.html',
                controller: 'SoundLocationsController'
            });
    }]);
