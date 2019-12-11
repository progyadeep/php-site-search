# PHP Site Search
A PHP script that searches your entire (PHP) website based on keywords and outputs relative URLs of appropriate pages along with their search score in a JSON array.

# Algo....um....the logic
It builds a list of all the files (yes, ALL THE FILES!) available in your website directory - even the ones inside sub-folders, sub-sub-folders and even **.hidden** folders and **.hidden** files themselves - excluding the ones you choose to <a href="#exclude-resources-from-search">exclude from the search</a>.

# Limitations
The script looks for exact word matches. That means, **"car"** and **"cars"** won't match with each other. To overcome this, you need toput both the words in the list of **meta keywords**. Sorry.

# Input/arguments to the script
Individual keywords. Comma separated, trimmed. For example:  

    https://www.example.com/search.php?q=keyword1,keyword2,apple,banana

# Output format
The following JSON array is returned:  

    {
      "path/to/local/file1":"search-score",
      "path/to/local/file2":"search-score"
    }
    
where `search-score` is an integer. The array is **sorted in decreasing order of search scores**. That means, the first result is the most relevant and the relevance decreases as the array index increases.

# What keywords!?
This script matches the supplied arguments with the meta keywords of the pages of your website, i.e., the keywords you specify in the HTML meta tags of the files like so:  

    <meta name="keywords" content="k1,k2, k3,k4"/>
It doesn't matter how many spaces there are between individual keywords; they should just be **comma-separated**.

# Exclude resources from search
Just put the relative URLs of the files that you do not want to appear in the search results (one per line) in the file **`ignore`**. You can also omit an entire folder by putting its relative URL.

# Code quality & intended usage
The code is **not at all** optimized for large number of files. It's a simple script that you can implement on your personal blog. I wrote this code as a past time activity. It works for sure but is definitely not the best.

# License
Use it as you want. Modifications are welcome. See license details in <a href="https://github.com/progyadeep/php-site-search/blob/master/LICENSE">LICENSE</a>.
