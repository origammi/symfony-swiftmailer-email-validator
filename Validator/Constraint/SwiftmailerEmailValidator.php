<?php

namespace SymfonySwiftmailerEmailValidator\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * An email validator which checks whether the validated Email address would
 * pass the check performed in SwiftMailer when sending and email.
 *
 * The validator extends the Symfony EmailValidator class and therefore supports
 * all the features of the Symfony email validation.
 *
 * https://github.com/swiftmailer/swiftmailer/issues/608
 *
 */
class SwiftmailerEmailValidator extends EmailValidator
{
    /**
     * @var \Swift_Mime_Grammar
     */
    protected $grammar;

    public function __construct($strict = false)
    {
        parent::__construct($strict);
        $this->grammar = new \Swift_Mime_Grammar();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        // First validate the value against the upstream validator
        $numViolationsBefore = count($this->context->getViolations());
        parent::validate($value, $constraint);
        $numViolationsAfter = count($this->context->getViolations());

        if (null === $value || '' === $value) {
            return;
        }

        // Then check whether SwiftMailer would actually accept the address.
        // Only perform this step if the previous validation of the field did
        // not yield any violations.
        if ($numViolationsBefore == $numViolationsAfter) {
            // The Swiftmailer grammar allows spaces in email addresses, we do
            // not want to allow that. Therefore, we check for that separately.
            if (!preg_match('/^'.$this->grammar->getDefinition('addr-spec').'$/D', $value)
                || preg_match('/\s/', $value)) {
                if ($this->context instanceof ExecutionContextInterface) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', $this->formatValue($value))
                        ->setCode(Email::INVALID_FORMAT_ERROR)
                        ->addViolation();
                } else {
                    $this->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', $this->formatValue($value))
                        ->setCode(Email::INVALID_FORMAT_ERROR)
                        ->addViolation();
                }

                return;
            }
        }
    }
}
