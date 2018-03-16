<?php

namespace SymfonySwiftmailerEmailValidator\Validator\Constraint;

use Symfony\Component\Validator\Constraints\Email;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class SwiftmailerEmail extends Email
{
}
