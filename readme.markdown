# Server Status for Status Board (SSfSB)

by Justin Michael

## Summary

A simple Linux/Mac server status panel for [Panic's Status Board](http://panic.com/statusboard/) that displays the following:

- Server Name
- System Load (Based on the last 15 minute average.)
- Memory Use
- Disk Use (For the drive the script is run from.)

<img src="ssfsb.png" style="width: 50%; height: 50%;" alt="Server Status for Status Board Screen Shot">

(The values shown in this screenshot are contrived to illustrate the color coding of the bars.  I hope your memory use is never this high!)

**Server Name** is determined using the `hostname` command, but can be overridden (see the Configuration section below).

**System Load** is based on the last 15 minute average (i.e. the third load average displayed by `uptime` and many other commands).

**Disk Use** is for the drive the `ssfsb.php` script is run from.

## Server Requirements

- Linux with kernel 3.14+ *or* Mac OS X 10.9+
- PHP 5.1.3+

## Installation

1. On the server you want to monitor, upload the `ssfsb.php` file to a publicly accessible location.
2. In Status Board on your iPad, add a new Do-It-Yourself panel and give it the URL to the `ssfsb.php` file you uploaded.

## Configuration

### Server Name

You can override the **Server Name** by doing the following:

1. Create a `ssfsb.name` file containing your desired server name.
2. Place it alongside the `ssfsb.php` file on your server.

### Resizing

The default size of this Status Board panel is 4 by 4 tiles.  The panel can be adjusted to between 4 and 16 tiles wide within Status Board.

## History

### 1.2

- The "Data is XX seconds old." line will now turn red if the data being displayed is *too* old, which helps you know at a glance if a server is having connectivity or responsiveness issues.

### 1.1

- Fixed memory percentage bar display bug.
- Fixed internal refresh interval conflict with Status Board refresh interval.
- Added override code for better screenshots.
- Created a better screenshot.

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