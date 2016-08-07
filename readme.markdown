# Server Status for Status Board (SSfSB)

Brought to you by Justin Michael at [Core Assistance](http://coreassistance.com/).

## Summary

A simple Linux/Mac server status panel for [Panic's Status Board](http://panic.com/statusboard/) that displays the following:

- Server Name
- System Load (Based on the last 15 minute average.)
- Memory Use
- Disk Use (For the drive the script is run from.)

TODO: Screenshot.

**Server Name** is determined using the `hostname` command, but can be overridden (see the Configuration section below).

**System Load** is based on the last 15 minute average (i.e. the third load average displayed by `uptime` and many other commands).

**Disk Use** is for the drive the `ssfsb.php` script is run from.

## Server Requirements

- Linux with kernel 3.14+ *or* Mac OS X 10.9+
- PHP 5.1.3+
- Any web server compatible with PHP.

## Installation

1. On the server you want to monitor, upload the `ssfsb.php` file to a publicly accessible location.
2. In Status Board on your iPad, add a new Do-It-Yourself panel and give it the URL to the `ssfsb.php` file you uploaded.

## Configuration

### Server Name

You can override the **Server Name** by doing the following:

1. Create a `ssfsb.name` file containing your desired server name.
2. Place it alongside the `ssfsb.php` file on your server.

## History

### 1.0

- Initial release.

## License

MIT License

Copyright (c) 2016 Core Assistance, LLC.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.