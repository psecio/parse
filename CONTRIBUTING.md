Contributing
============

First of all, **thank you** for contributing!

Here are a few rules to follow in order to ease code reviews and merging:

- follow [PSR-1](http://www.php-fig.org/psr/psr-1/) and [PSR-2](http://www.php-fig.org/psr/psr-2/)
- run the test suite
- write (or update) unit tests when applicable
- write documentation for new features
- use [commit messages that make sense](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)

When creating your pull request on GitHub, please write a description which gives the context and/or explains why you are creating it.


Naming new rules
----------------
Rule names should be consistent. To help ensure this, we use a simple naming convention:

    <what><optional-when>

That is, we name what the rulw is about and potentially add some modifiers saying when it should be applied..

An example of just a what would be `EvalFunction`. Since `eval()` is (almost) always bad, we don't need to modify the name. Another would be `BooleanIdentity`, which enforces always using the identical operator (`===`) instead of the equals operator (`==`). There's not much more involved in the concept so a modifier isn't needed.

An example of a when modifier is `EchoWithFileGetContents`. In general, using `echo` is safe and necessary. However, `echo file_get_contents()` is potentially a bad thing. There is potential for several rules to be about using `echo` with something dangerous; adding `WithFileGetContents` makes it clear what's being tested.
