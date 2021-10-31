# Xcode build times

⚠️ Monterey users -> see [Issue #17](https://github.com/matopeto/xcode-build-times/issues/17)
TL;DR; -> You must install PHP -> `brew install php`

------

# Xcode build times

![](screenshots/menubar.png)

Have you ever wondered how much time a day you spend waiting for Xcode to do your builds? Wonder no more, this [xbar](https://github.com/matryer/xbar)(the BitBar reboot) or [SwiftBar](https://github.com/swiftbar/SwiftBar/) plugin shows the time wasted right in your menu bar!

![](screenshots/menubar-extended.png)

## Installation

You can use this plugin with xbar (the BitBar reboot) or newer Swiftbar (in development)

So first install [xbar](https://github.com/matryer/xbar#get-started) or [SwiftBar](https://github.com/swiftbar/SwiftBar#how-to-get-swiftbar)

On the first run select a directory you wish to use as your plugin directory, for example `~/BitBarPlugins`.

If you are using macOS Monterey then you must install PHP, because it is not bundled in macOS anymore. see [Issue #17](https://github.com/matopeto/xcode-build-times/issues/17) e.g. from Homebrew: `brew install php`

### Plugin installation

Download the `xcodeBuildTimes.1m.php` file from the `sources` folder in this repository and place it the plugin folder and make it executable.

You can do it manually or via terminal

```bash
cd ~/BitBarPlugins
curl https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/xcodeBuildTimes.1m.php --output xcodeBuildTimes.1m.php
chmod +x xcodeBuildTimes.1m.php
```

If you now refresh xbar/SwiftBar data you should see the script being loaded.

### Xcode setup

The final step is to make Xcode call the script on every build. 

To do this open `Preferences` | `Behaviors` in Xcode and set the script to `Run` when the `Build` starts

![](screenshots/xcode-start.png)

fails

![](screenshots/xcode-fail.png)

and succeeds

![](screenshots/xcode-finish.png)

### Optional setup

The script is called `xcodeBuildTimes.1m.php` so xbar/SwiftBar will refresh the data every minute. If you want to use a different refresh interval, just change the `1m` in the script name to your desired interval. 

You can find [more info about the refresh intervals in the xbar/SwiftBar documentation](https://github.com/matryer/xbar#configure-the-refresh-time).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
