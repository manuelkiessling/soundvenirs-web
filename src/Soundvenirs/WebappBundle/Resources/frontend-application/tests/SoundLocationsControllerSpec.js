describe('SoundLocationsController', function() {
    var scope, controller, httpBackend;

    // Initialization of the AngularJS application before each test case
    beforeEach(module('SoundvenirsApp'));

    // Injection of dependencies, $http will be mocked with $httpBackend
    beforeEach(inject(function($rootScope, $controller, $httpBackend) {
        scope = $rootScope;
        controller = $controller;
        httpBackend = $httpBackend;
    }));

    it('should put a list of all soundLocations on the scope', function() {

        // We expect the controller to ask the API for the soundLocations
        httpBackend.expectGET('/api/soundLocations')
            .respond('[{"title": "First Song", "location": {"lat": 11.1, "long": 1.11}}]');

        // Start the controller
        controller('SoundLocationsController', {'$scope': scope });

        // Respond to all HTTP requests
        httpBackend.flush();

        // Trigger the AngularJS digest cycle in order to resolve all promises
        scope.$apply();

        expect(scope.soundLocations[0].title).toEqual('First Song');

    });

});
