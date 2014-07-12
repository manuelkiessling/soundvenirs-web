describe('Webapp SoundLocations', function() {
    it('should have a title', function() {
        browser.get('http://localhost:8080/app_test.php/app/#/');
        expect(browser.getTitle()).toEqual('Soundvenirs');
    });
});
