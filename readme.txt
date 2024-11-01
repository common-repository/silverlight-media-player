=== Plugin Name ===
Contributors: Tim Heuer
Tags: silverlight, microsoft, tim heuer, wpf, xaml, smf
Requires at least: 2.0.2
Tested up to: 3.0.0
Stable tag: 1.1.1

Silverlight Media Player for WordPress allows a WordPress user to specify media URL (either normal download or Smooth Streaming) to embed video in their WordPress content.

== Description ==

Microsoft Silverlight is a browser plugin for enabling rich internet and media applications.  This plugin makes it easy for WordPress content authors
to add Silverlight media content - including Smooth Streaming content - to their blog posts and/or pages using a simple command to specify the location of the media.  This player uses the Silverlight Media Framework 2.0, an Open Source project from Microsoft (http://smf.codeplex.com).

== Installation ==

1. Extract all files and copy it to your plugins directory (/wp-content/plugins/).
2. Login to Wordpress Admin and activate the plugin.
3. Goto the Settings area and update your prefered default values for width, height and minimum version.
    - Standard Width: the default width of the Silverlight plugin
    - Standard Height: the default height of the Silverlight plugin
    - Standard Version: the minimum required Silverlight version required

A media player can be embedded in a post using a tag of the following form:

[sl-media: URL]

e.g. [sl-media: http://location.com/to/my/video/file/movie.mp4]

You can also override the default dimensions:

[silverlight: URL, WIDTH, HEIGHT]

e.g. [sl-media: http://location.com/to/my/video/file/movie.mp4, 400, 300]

If the content is an IIS Smooth Streaming content, specify the flag correctly (requires size to be set also -- last flag specifies Smooth Streaming content):

e.g. [sl-media: http://location.com/to/my/video/file/movie.ism/Manifest, 400, 300, true]

You can now also specify a Thumbnail preview image:

e.g. [sl-media: http://location.com/to/my/video/file/movie.mp4, 400, 300, false, http://location.com/to/my/preview/image/preview.png]

== Frequently Asked Questions ==

= Does this work with any media file? =

No, this requires the media to be a supported type by Silverlight.  This does include, however, H.264 encoded media in an MP4 container.

== Screenshots ==

== Support ==
For support visit http://timheuer.com/silverlight-for-wordpress or contact Tim directly at tim@timheuer.com.