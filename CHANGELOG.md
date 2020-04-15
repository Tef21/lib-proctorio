CHANGELOG
=========

2.0.0
-----
- Add validator for `ProctorioConfig` payload.
- Validate response from Proctorio inside library.
- Removed externalization of Guzzle exception to library clients. 
- Return self-contained `ProctorioResponse` object instead of raw array.

1.0.0
-----
- First proctorio stable release.
- Possibility to request testTaker and testReviewers URLs from proctorio.
