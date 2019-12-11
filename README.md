# PHP Site Search
A PHP script that searches your entire (PHP) website based on keywords and outputs relative URLs of appropriate pages along with their search score in a JSON array.

# Algo....um....the logic
It builds a list of all the files (yes, ALL THE FILES!) available in your website directory - even the ones inside sub-folders, sub-sub-folders and even **.hidden** folders and **.hidden** files themselves - excluding the ones you choose to <a href="#exclude-resources-from-search">exclude from the search</a> - and then reads the meta keywords from the files and matches them with the queried keywords.

# Input/arguments to the script
Individual keywords. Comma separated, trimmed. For example:  

    https://www.example.com/search.php?q=keyword1,keyword2,apple,banana

# Output format
The following JSON array is returned:  

    {
      "path/to/local/file1":"search-score",
      "path/to/local/file2":"search-score"
    }
    
where `search-score` is an integer. The array is **sorted in decreasing order of search scores**. That means, the first result is the most relevant and the relevance decreases as the array index increases. It does not contain any element with score less than 1.  

In case of 0 (zero) search results, it will return an empty JSON array like so:  

    {}

# What keywords!?
This script matches the supplied arguments with the meta keywords of the pages of your website, i.e., the keywords you specify in the HTML meta tags of the files like so:  

    <meta name="keywords" content="k1,k2, k3,k4"/>
It doesn't matter how many spaces there are between individual keywords; they should just be **comma-separated**.

# Exclude resources from search
Just put the relative URLs of the files that you do not want to appear in the search results (one per line) in the file **`ignore`**. You can also omit an entire folder by putting its relative URL.

# Code quality & intended usage
The code is **not at all** optimized for large number of files. It's a simple script that you can implement on your personal blog. I wrote this code as a past time activity. It works for sure but is definitely not the best (Complexity: O(n<sup>3</sup>)).

# License
Use it as you want. Modifications are welcome. See license details in <a href="https://github.com/progyadeep/php-site-search/blob/master/LICENSE">LICENSE</a>.
