/**
 * Controller for the SoundLocations screen
 *
 * @see services
 */
angular.module('soundvenirsWebapp')
    .controller('SoundLocationsController', function ($scope, SoundLocationsService) {

        $scope.showFindlocationInfo = true;

        var geolocationFound = function (position) {
            $scope.showFindlocationInfo = false;
            $scope.map = {
                center: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },
                zoom: 9
            };
            $scope.$apply();
        };

        var geolocationNotfound = function (error) {
            $scope['show-findlocation-info'] = false;
        };

        if (navigator.geolocation) {
            console.log('0');
            navigator.geolocation.getCurrentPosition(
                geolocationFound,
                geolocationNotfound,
                {
                    enableHighAccuracy: true,
                    maximumAge : 30000,
                    timeout : 27000
                }
            );
        }

        $scope.map = {
            center: {
                latitude: 23.3253854,
                longitude: 8.0937441
            },
            zoom: 3
        };

        SoundLocationsService.getAll().then(function(soundLocations) {
            for (var i = 0; i < soundLocations.length; i++) {
                soundLocations[i].randomId = Math.random();
            }
            $scope.soundLocations = soundLocations;
        });
    });
