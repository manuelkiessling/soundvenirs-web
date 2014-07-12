/**
 * Restangular-based data service, fetches soundLocations data from the backend
 *
 * @see https://github.com/mgonto/restangular
 */
angular.module('soundvenirsWebapp')
    .factory('SoundLocationsService', ['CONFIG_API_ENDPOINT', 'Restangular', function SoundLocationsService(CONFIG_API_ENDPOINT, Restangular) {
        return {
            /**
             * @function getAll
             * @returns a Promise that eventually resolves to the list of all available sound locations
             */
            getAll: function() {
                Restangular.setBaseUrl(CONFIG_API_ENDPOINT);
                return Restangular.one('soundLocations').getList();
            }
        };
    }]);
