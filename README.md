# Symfony Swiftmailer Email Validator

A PHP library that adds a Symfony validator which validates fields against the
same rules as Swiftmailer does when checking email addresses before sending them
out.

The primary purpose is to have a tool which enables developers to ensure when
they accept an email from an input that Swiftmailer will later, when sending an
email, will not refuse the email address.


## TODO

- Make library compliant with Symfony >= 3.0. Currently we are using deprecated
  API when adding constraint violations.
- Make library compliant with Swiftmailer >= 6.0. They refactored the way they
  define their email address grammar.
