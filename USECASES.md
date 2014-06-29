# Entity definitions

## Sound

* `id`: string, unique id
* `title`: string, name of sound
* `location`: `location` entity, depicts position of sound; `null` if sound has not yet been tagged with a position
* `mp3url`: string, fully qualified URL to mp3 sound file

API endpoint: `http://www.soundvenirs.com/api/sounds`

JSON examples:

    {
      "id": "9za8hu",
      "title": "The Sound of Silence",
      "location": {
        "lat": 51.394747,
        "long": 6.494785
      },
      "mp3url": "http://www.soundvenirs.com/download/9za8hu.mp3"
    }

    {
      "id": "9za8hu",
      "title": "The Sound of Silence",
      "location": null,
      "mp3url": "http://www.soundvenirs.com/download/9za8hu.mp3"
    }


## Location

API endpoint: none

* `lat`: float, latitude of sound position
* `long`: float, longitude of sound position

JSON example:

    {
      "lat": 51.394747,
      "long": 6.494785
    }


## SoundLocation

API endpoint: `http://www.soundvenirs.com/api/soundLocations`

* `title`: string, title of `sound` entity at position
* `location`: `location` entity, depicts position of sound

JSON example:

    {
      "title": "The Sound of Silence",
      "location": {
        "lat": 51.394747,
        "long": 6.494785
      },
    }


# Case 1: Creating a new sound

* Artist creates mp3 sound file
* Artist visits website and uploads mp3 file
* Backend creates sound entity with id, title and mp3 file name, writes mp3 file to disk
* Website displays the QR code of `http://sndvnr.com/s/:id`, where `:id` is the id of the uploaded sound
* Artist prints QR code, visits target location, pins QR code image at target location
* Artist uses app to scan QR code, app extracts the `id` and sends a POST request to
  `www.soundvenirs.com/api/sounds/:id`, with a `location` entity that has the lat and long of the current device
  position
* Backend has not yet set a location for the sound with this `id` and therefore creates a new `location` entity
  and sets the location attribute of the sound entity to the `location` entity
* Backend responds with `true`, which the app interprets as "location has been set, do not retrieve sound"


# Case 2: Consuming a sound

* User uses app to list sounds in the proximity
* App sends a GET request to `www.soundvenirs.com/api/soundLocations`, backend responds with an array of
  all `soundLocation` entities the system knows about, that is, a position with a title for all sounds that already
  have a location set
* App displays all (nearby) sound locations on map
* User visits one of the locations and finds the QR code image
* User scans QR code image using the app, app sends a POST request to `www.soundvenirs.com/api/sounds/:id`,
  with a `location` object that has the lat and long of the current device position
* Backend already knows the location for the sound with the given `id`, therefore does nothing and responds with
  `false`, which the app interprets as "location of sound known, sound can be retrieved"
* App sends a GET request to `www.soundvenirs.com/api/sounds/:id`, which responds with the `sound` entity with the
  given `id`
* App downloads mp3 file from URL at `sound.mp3url` and plays sound
