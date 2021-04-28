<?php

namespace tuana8tmt\TextExtract;

class Index
{
    public function extract($text, $stopwords)
    {
        $stopwords = file($stopwords);
        // Remove line breaks and spaces from stopwords
        $stopwords = array_map(function($x){return trim(strtolower($x));}, $stopwords);

        // Replace all non-word chars with comma
        $pattern = '/[0-9\W]/';
        $text = preg_replace($pattern, ',', $text);

        // Create an array from $text
        $text_array = explode(",",$text);

        // remove whitespace and lowercase words in $text
        $text_array = array_map(function($x){return trim(strtolower($x));}, $text_array);

        foreach ($text_array as $term) {
            if (!in_array($term, $stopwords)) {
                $keywords[] = $term;
            }
        }

        return array_filter($keywords);

    }
}