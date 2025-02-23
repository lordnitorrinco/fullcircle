<?php

namespace App\Services\Arrays;

class FindNthLargestArray
{
    /**
     * Finds the nth largest number in an array.
     *
     * @param array $arr The input array of numbers.
     * @param int $n The position of the largest number to find.
     * @return int|null The nth largest number in the array, or null if the array has fewer than n elements.
     */
    public function execute(array $arr, int $n): ?int
    {
        /**
         * The following code snippet is an alternative implementation of the function using the built-in PHP functions rsort() and count():
         * 
         *  if (count($arr) < $n) return null;
         *  rsort($arr);
         *  return $arr[$n - 1];
         * 
         */

        // Count the number of elements in the array
        $length = 0;
        foreach ($arr as $value) {
            $length++;
        }

        if ($length < $n) return null;

        // Find the nth largest number using a selection algorithm
        for ($i = 0; $i < $n; $i++) {
            $maxIndex = $i;
            for ($j = $i + 1; $j < $length; $j++) {
                if ($arr[$j] > $arr[$maxIndex]) {
                    $maxIndex = $j;
                }
            }
            // Swap the found maximum element with the element at index i
            $temp = $arr[$i];
            $arr[$i] = $arr[$maxIndex];
            $arr[$maxIndex] = $temp;
        }

        return $arr[$n - 1];
    }
}
