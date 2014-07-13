describe('Webapp SoundLocations', function() {
    it('should bootstrap the app', function() {
        browser.get('http://localhost:8080/app_test.php/app/#/');
        expect(element(by.css('body')).getAttribute('ng-app')).toBe('soundvenirsWebapp');
    });
});
