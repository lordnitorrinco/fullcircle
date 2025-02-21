<?php

/**
 * Finds the second largest number in an array.
 *
 * This function takes an array of numbers as input and returns the second largest number in the array.
 * If the array has fewer than two elements, it returns null.
 *
 * @param array $arr The input array of numbers.
 * @return int|null The second largest number in the array, or null if the array has fewer than two elements.
 */
function secondLargest($arr) {
    // Check if the array has fewer than two elements
    if (count($arr) < 2) return null;

    // Initialize the first and second largest numbers to the smallest possible integer value
    $first = $second = PHP_INT_MIN;

    // Iterate through each number in the array
    foreach ($arr as $num) {
        // If the current number is greater than the first largest number
        if ($num > $first) {
            // Update the second largest number to be the previous largest number
            $second = $first;
            // Update the first largest number to be the current number
            $first = $num;
        // If the current number is greater than the second largest number and not equal to the first largest number
        } elseif ($num > $second && $num != $first) {
            // Update the second largest number to be the current number
            $second = $num;
        }
    }

    // Return the second largest number
    return $second;
}
// Generate an array of random integers with between 2 and 10 elements
$array = array_map(function() {
    return rand(1, 100);
}, range(1, rand(2, 10)));

// Output the array
echo "Array: [" . implode(',', $array) . "]" . PHP_EOL;

// Call the secondLargest function and output the result
$result = secondLargest($array);
echo "Second largest element: $result" . PHP_EOL;