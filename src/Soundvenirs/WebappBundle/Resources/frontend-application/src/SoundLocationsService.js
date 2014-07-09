/**
 * Restangular-based data service, fetches soundLocations data from the backend
 *
 * @see https://github.com/mgonto/restangular
 */
angular.module('SoundvenirsApp')
    .factory('SoundLocationsService', ['Restangular', '$q', function SoundLocationsService(Restangular, $q) {
        return {
            /**
             * @function getAll
             * @returns a Promise that eventually resolves to the list of all available sound locations
             */
            getAll: function() {
                return Restangular.one('api/soundLocations').getList();
            }
        };
    }]);
