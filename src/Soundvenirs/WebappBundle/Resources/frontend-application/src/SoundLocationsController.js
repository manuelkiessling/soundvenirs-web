/**
 * Controller for the SoundLocations screen
 *
 * @see services
 */
angular.module('soundvenirsWebapp')
    .controller('SoundLocationsController', function ($scope, SoundLocationsService) {
        $scope.map = {
            center: {
                latitude: 45.000,
                longitude: -73.000
            },
            zoom: 8
        };

        SoundLocationsService.getAll().then(function(soundLocations) {
            for (var i = 0; i < soundLocations.length; i++) {
                soundLocations[i].randomId = Math.random();
            }
            $scope.soundLocations = soundLocations;
        });
    });
