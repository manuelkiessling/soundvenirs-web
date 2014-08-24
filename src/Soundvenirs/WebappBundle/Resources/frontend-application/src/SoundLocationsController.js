/**
 * Controller for the SoundLocations screen
 *
 * @see services
 */
angular.module('soundvenirsWebapp')
    .controller('SoundLocationsController', function ($scope, SoundLocationsService) {
        $scope.map = {
            center: {
                latitude: 45,
                longitude: -73
            },
            zoom: 8
        };

        SoundLocationsService.getAll().then(function(soundLocations) {
            $scope.soundLocations = soundLocations;
        });
    });
