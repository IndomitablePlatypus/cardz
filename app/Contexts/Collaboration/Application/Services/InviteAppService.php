<?php

namespace App\Contexts\Collaboration\Application\Services;

use App\Contexts\Collaboration\Domain\Model\Invite\InviteId;
use App\Contexts\Collaboration\Infrastructure\Persistence\Contracts\InviteRepositoryInterface;
use App\Contexts\Collaboration\Infrastructure\Persistence\Contracts\KeeperRepositoryInterface;
use App\Contexts\Collaboration\Integration\Events\InviteAccepted;
use App\Shared\Contracts\ReportingBusInterface;
use App\Shared\Contracts\ServiceResultFactoryInterface;
use App\Shared\Contracts\ServiceResultInterface;
use App\Shared\Infrastructure\Support\ReportingServiceTrait;

class InviteAppService
{
    use ReportingServiceTrait;

    public function __construct(
        private KeeperRepositoryInterface $keeperRepository,
        private InviteRepositoryInterface $inviteRepository,
        private ReportingBusInterface $reportingBus,
        private ServiceResultFactoryInterface $serviceResultFactory,
    ) {
    }

    public function accept(string $inviteId): ServiceResultInterface
    {
        $invite = $this->inviteRepository->take(InviteId::of($inviteId));
        if ($invite === null) {
            return $this->serviceResultFactory->notFound("Invite $inviteId not found");
        }
        $invite->accept();
        $this->inviteRepository->persist($invite);

        $result = $this->serviceResultFactory->ok($invite->inviteId, new InviteAccepted($invite->inviteId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function reject(string $inviteId): ServiceResultInterface
    {
        $invite = $this->inviteRepository->take(InviteId::of($inviteId));
        if ($invite === null) {
            return $this->serviceResultFactory->notFound("Invite $inviteId not found");
        }
        $invite->reject();
        $this->inviteRepository->remove($invite->inviteId);

        $result = $this->serviceResultFactory->ok($invite->inviteId, new InviteAccepted($invite->inviteId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function discard(string $inviteId): ServiceResultInterface
    {
        $invite = $this->inviteRepository->take(InviteId::of($inviteId));
        if ($invite === null) {
            return $this->serviceResultFactory->notFound("Invite $inviteId not found");
        }
        $invite->discard();
        $this->inviteRepository->remove($invite->inviteId);

        $result = $this->serviceResultFactory->ok($invite->inviteId, new InviteAccepted($invite->inviteId));
        return $this->reportResult($result, $this->reportingBus);
    }
}
