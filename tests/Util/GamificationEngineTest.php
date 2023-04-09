<?php

namespace App\Tests\Util;

use App\Entity\User;
use App\Util\GamificationEngine;
use PHPUnit\Framework\TestCase;

class GamificationEngineTest extends TestCase
{
    /**
     * @dataProvider computeLevelForUserProvider
     */
    public function testComputeLevelForUser(int $exp, int $expectedLevel): void
    {
        $user = new User();
        $user->addExperience($exp);

        $this->assertSame($expectedLevel, GamificationEngine::computeLevelForUser($user));
    }

    public function computeLevelForUserProvider(): array
    {
        return [
            [50, 1],
            [100, 2],
            [150, 2],
            [200, 2],
            [420, 3],
            [1500, 6],
        ];
    }

    /**
     * @dataProvider computeExperienceNeededForLevelProvider
     */
    public function testComputeExperienceNeededForLevel(int $level, int $expectedExp): void
    {
        $this->assertSame($expectedExp, GamificationEngine::computeExperienceNeededForLevel($level));
    }

    public function computeExperienceNeededForLevelProvider(): array
    {
        return [
            [1, 0],
            [2, 100],
            [3, 300],
            [4, 600],
        ];
    }

    /**
     * @dataProvider computeLevelCompletionForUserProvider
     */
    public function testComputeLevelCompletionForUser($experience, $levelCompletion): void
    {
        $user = new User();
        $user->addExperience($experience);

        $this->assertSame($levelCompletion, GamificationEngine::computeLevelCompletionForUser($user));
    }

    public function computeLevelCompletionForUserProvider(): array
    {
        return [
            [0, 0],
            [10, 10],
            [60, 60],
            [99, 99],
            [300, 0],
            [360, 20],
            [400, 33],
            [450, 50],
            [540, 80],
        ];
    }
}
