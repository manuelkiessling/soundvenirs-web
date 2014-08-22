var path = require('path');

describe('Uploading a soundfile', function() {

    beforeEach(function() {
        // The homepage is not an Angular, thus we don't wait for one to load
        return browser.ignoreSynchronization = true;
    });

    it('should return a QR code', function() {
        var page = browser.driver.get('http://localhost:8080/app_test.php/');

        var fileToUpload = '../assets/soundfile.mp3';
        var absolutePath = path.resolve(__dirname, fileToUpload);

        // Ghostdriver won't find invisible elements
        browser.driver.executeScript('document.getElementById("form").setAttribute("style", "visibility: visible;");');

        element(by.css('#form_soundfile')).sendKeys(absolutePath);
        element(by.css('#upload_form')).submit();

        element(by.css('body')).getText().then(function(text) {
           expect(text).toBe('Use the Print function of your browser to print this QR code. Then place the printed QR code at the target location. While at the target location, scan the code for the first time using the Soundvenirs mobile app. Now visitors at the target location will be able to scan the code and listen to your sound.');
        });
    });

});
