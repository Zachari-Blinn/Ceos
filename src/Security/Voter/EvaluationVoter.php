<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EvaluationVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'DELETE', 'VIEW'])
            && $subject instanceof \App\Entity\Evaluation;
    }

    protected function voteOnAttribute($attribute, $evaluation, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if(null == $evaluation->getEnseignement()->getProf()->getUser())
        {
            return false;
        }

        switch ($attribute)
        {
            case 'EDIT':
                if ($evaluation->getEnseignement()->getProf()->getUser() == $user)
                {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN'))
                {
                    return true;
                }
                break;
            case 'DELETE':
                if ($evaluation->getEnseignement()->getProf()->getUser() == $user)
                {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN'))
                {
                    return true;
                }
                break;
            case 'VIEW':
                if ($evaluation->getEnseignement()->getProf()->getUser() == $user)
                {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN'))
                {
                    return true;
                }
                break;
        }
        return false;
    }
}
