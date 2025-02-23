<?php
namespace App\Services\Arrays;

class FindSecondLargestArray
{
    private FindNthLargestArray $findNthLargestService;

    public function __construct()
    {
        $this->findNthLargestService = new FindNthLargestArray();
    }

    public function findSecondLargestArray(array $arr): ?int
    {
        return $this->findNthLargestService->execute($arr, 2);
    }
}