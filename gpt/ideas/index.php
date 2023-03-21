<!-- partial:index.partial.html -->
<div class="master-box">
  
  <div class="faq-header">
    <div class="filter-search">
      <input type="text" id="myInput" onkeyup="searchFunction()" placeholder="Search">
      <div id="myBtnContainer">
        <button class="btn-chat active" onclick="filterSelection('all')"> Show all</button>
        <button class="btn-chat" onclick="filterSelection('answers')">Answers</button>
        <button class="btn-chat" onclick="filterSelection('advertising')">Advertising</button>
        <button class="btn-chat" onclick="filterSelection('blogging')">Blogging</button>
        <button class="btn-chat" onclick="filterSelection('copywriting')">Copywriting</button>
        <button class="btn-chat" onclick="filterSelection('creative')">Creative</button>
        <button class="btn-chat" onclick="filterSelection('generation')">Generation</button>
        <button class="btn-chat" onclick="filterSelection('support')">Support</button>
        <button class="btn-chat" onclick="filterSelection('code')">Coding</button>
        <button class="btn-chat" onclick="filterSelection('design')">Design</button>
        <button class="btn-chat" onclick="filterSelection('definitions')">Definitions</button>
        <button class="btn-chat" onclick="filterSelection('jobs')">Jobs</button>
        <button class="btn-chat" onclick="filterSelection('marketing')">Marketing</button>
        <button class="btn-chat" onclick="filterSelection('products')">Products</button>
        <button class="btn-chat" onclick="filterSelection('education')">Education</button>
      </div>
    </div>  
  </div>
  
  <ul id="myUL">
    <li class="filterDiv advertising">
      <span class="accordion-thumb">Display Ads</span>
      <p class="accordion-panel">

Act as an advertiser and create a campaign to promote a new fashion line targeting women in their 30s<br><br>
		  
	  </p>
    </li>
    <li class="filterDiv answers">
      <span class="accordion-thumb">Question - <a style="color:#fff" href='https://platform.openai.com/examples/default-factual-answering'>API LINK</a></span>
      <p class="accordion-panel">
		  Who is/was...what is/was...where is/was...how many...how to/does...tell me...suggest...<br><br>

Examples:<br><br>

What's:<br>
Q: What is the capital of California?<br>
Q: What is the cheapest way to travel to....?<br>
Q: What is human life expectancy in the United States?<br><br>
Q: What is the specific career path for Architecture, also job growth, average salary, and necessary qualifications.<br><br>

Where's:<br>
Q: Where were the 1992 Olympics held?<br>
Q: Where is the Valley of Kings?<br>

Who's<br><br>
Q: Who is...Batman?<br>
Q: Who was president of the United States in 1955?<br><br>

How's:<br>
Q: How many...moons does Mars have?<br>
Q: How does a telescope work?<br><br>

Tell's:<br>
Q: Tell me about when Christopher Columbus came to the US in 2015<br><br>
Q: Tell me the pros and cons about over-sleeping<br>
		 
Suggest:<br>
Q: Suggest 10 birthday gifts for a 40 year old man<br><br>	 
		 
	  </p>
    </li>
    <li class="filterDiv creative generation">
      <span class="accordion-thumb">Analogy maker - <a style="color:#fff" href='https://platform.openai.com/examples/default-analogy-maker'>API LINK</a></span>
      <p class="accordion-panel">
		  Create an analogy for this phrase<br><br>

Examples:<br>
Create an analogy for this phrase:<br>
Questions are arrows in that:<br>
		  
	  </p>
    </li>
    <li class="filterDiv blogging generation">
      <span class="accordion-thumb">Blog Content</span>
      <p class="accordion-panel">
		  Write a blog.../ make it 200 words and simplier to understand<br><br>

Examples:<br><br>
Write blog post about the benefits of healthy diet.<br><br>
Make it 200 words and simplier to understand.<br><br>
Write blog titles about healthy diet.<br><br>
Write blog section about healthy diet.<br><br>
Give me blog ideas about healthy diet .<br><br>
Write me blog intros about healthy diet.<br><br>
Write me a blog conclusion about healthy diet.<br><br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Book Suggestions </span>
      <p class="accordion-panel">
		  List 10....books<br><br>

Examples:<br>
List 10 science fiction books.<br>
		  
	  </p>
    </li>
    <li class="filterDiv support marketing">
      <span class="accordion-thumb">Emails</span>
      <p class="accordion-panel">

Write customer email apologising for.... <br><br>
Write an email that encourages the potential client to reply to my message and book a pet grooming appointment for their dog.<br><br>	  	  
Write an email campaign to promote our new line of organic skincare products to our subscribed customers

	  </p>
    </li>
    <li class="filterDiv code">
      <span class="accordion-thumb">Debug code</span>
      <p class="accordion-panel">This code is not working like i expect — how do i fix it? - <a style="color:#fff" href='https://platform.openai.com/examples/default-js-one-line'>API LINK</a></span>
    </li>
    <li class="filterDiv code">
      <span class="accordion-thumb">Write or learn code</span>
      <p class="accordion-panel">
		  Write... / turn...<br><br>

Examples:<br><br>
Write simple code to track cyptro prices so I can buy or sell?<br><br>
Turn a JavaScript function into a one liner.<br><br>
Convert simple JavaScript expressions into Python.<br><br>
Create a SQL request to find all users who live in California and have over 1000 credits:<br><br>
Act as a professional coder and teach me how to code in Java...I don’t understand if and else statements etc.<br><br>

	  </p>
    </li>
    <li class="filterDiv creative generation">
      <span class="accordion-thumb">Design Covers</span>
      <p class="accordion-panel">

CD Covers design (as service or sell)<br><br>
Book cover design (as service or sell)<br><br>
		  
	  </p>
    </li>
    <li class="filterDiv creative generation">
      <span class="accordion-thumb">Artwork</span>
      <p class="accordion-panel">

Paintings (print and sell or provide service - turn your photos into works of art, any style)<br><br>
Comic books? (create comic book from a script)<br><br>
		
Examples:<br><br>
Give me a {TITLE) of a (GENTRE, Fantasy movie) containing (SCENRIO) in a fantasy setting.<br><br>
Give me a movie poster of a horror movie titled...	 
		  
	  </p>
    </li>
    <li class="filterDiv account answers">
      <span class="accordion-thumb">Definitions</span>
      <p class="accordion-panel">
		  Explain...<br><br>

Examples:<br><br>
Explain parody<br><br>
		  
	  </p>
    </li>
    <li class="filterDiv copywriting generation">
      <span class="accordion-thumb">Essays - <a style="color:#fff" href='https://platform.openai.com/examples/default-essay-outline'>API LINK</a></span>
      <p class="accordion-panel">
		  Create an outline for an essay about...<br><br>

Example:<br><br>
Create an outline for an essay about Nikola Tesla and his contributions to technology:<br><br>
		  
	  </p>
    </li>
    <li class="filterDiv jobs generation">
      <span class="accordion-thumb">Interview Questions - <a style="color:#fff" href='https://platform.openai.com/examples/default-interview-questions'>API LINK</a></span>
      <p class="accordion-panel">
		  Create a list of...<br><br>

Example:<br><br>
Create a list of 8 questions for my interview with a science fiction author:<br><br>
		  
	  </p>
    </li>
    <li class="filterDiv creative generation">
      <span class="accordion-thumb">Music</span>
      <p class="accordion-panel">
		  Write me a.../ Compose music...<br><br>

Examples:<br><br>
Write me a jingle about breakfast cereal and includes the words vitamins and minterals.<br><br>
Write a song about the benefits of social media. <a style="color:#fff" href='https://platform.openai.com/examples/default-interview-questions'>Convert text to song</a><br><br>
Write a poem about love.<br><br>
Write a song with these words:<br><br>
Compose music in style of star wars? (Be composer? Type in what you want, send to musican to perform)<br><br>
 
	  </p>
    </li>
    <li class="filterDiv marketing">
      <span class="accordion-thumb">Keywords - <a style="color:#fff" href='https://platform.openai.com/examples/default-keywords'>API LINK</a></span>
      <p class="accordion-panel">

Extract keywords from this text:<br><br>
What are some trending keywords I can use for...<br><br>

TIP: Import posts, extract keywords, use for hashtags<br><br>
 
	  </p>
    </li>
    <li class="filterDiv marketing">
      <span class="accordion-thumb">Marketing Stratgey</span>
      <p class="accordion-panel">

Can you create me a marketing stratgey for...<br><br>
What marketing strategy should I use for...<br><br>
		 
	  </p>
    </li>
    <!--<li class="filterDiv account promotion bugs">
      <span class="accordion-thumb">Can you tell me what am I looking for?</span>
      <p class="accordion-panel">Purus non enim praesent elementum facilisis. Platea dictumst quisque sagittis purus sit. Nec dui nunc mattis enim. Vitae congue eu consequat ac felis donec et odio pellentesque. Pulvinar elementum integer enim neque volutpat. Vel facilisis volutpat est velit egestas dui. Eget arcu dictum varius duis at consectetur lorem. Diam sollicitudin tempor id eu nisl nunc mi ipsum. Vitae auctor eu augue ut. Sed libero enim sed faucibus turpis in. Mauris sit amet massa vitae.</p>
    </li>-->
    <li class="filterDiv copywriting generation">
      <span class="accordion-thumb">Notes - <a style="color:#fff" href='https://platform.openai.com/examples/default-notes-summary'>NOTES API LINK</a> / <a style="color:#fff" href='https://platform.openai.com/examples/default-study-notes'>STUDY NOTES API LINK</a></span>
      <p class="accordion-panel">
		  Write a short note.../ Convert my short hand.../ What are...<br><br>

Notes:<br><br>
Write a short note to introduce myself to my neighbor...can you make it more formal?<br><br>
Convert my short hand into a first-hand account of the meeting:<br><br>
 
Study notes:<br><br>
What are 5 key points I should know when studying Ancient Rome?

	  </p>
    </li>	
    <li class="filterDiv marketing">
      <span class="accordion-thumb">Product ideas - <a style="color:#fff" href='https://platform.openai.com/examples/default-vr-fitness'>API LINK</a></span>
      <p class="accordion-panel">
		  Brainstorm some ideas combining...<br><br>

Examples:<br><br>
Brainstorm some ideas combining VR and fitness:<br><br>
 
	  </p>
    </li>
    <li class="filterDiv marketing">
      <span class="accordion-thumb">Product name generator - <a style="color:#fff" href='https://platform.openai.com/examples/default-product-name-gen'>API LINK</a></span>
      <p class="accordion-panel">

Product description: A home milkshake maker<br>
Seed words: fast, healthy, compact.<br><br>

Product description: A pair of shoes that can fit any foot size.<br>
Seed words: adaptable, fit, omni-fit.<br><br>
 
Product Name Generator
 
	  </p>
    </li>
    <li class="filterDiv marketing advertising">
      <span class="accordion-thumb">Product description - <a style="color:#fff" href='https://platform.openai.com/examples/default-ad-product-description'>API LINK</a></span>
      <p class="accordion-panel">
		  Write a creative ad for the following product...<br><br>

Examples:<br><br>
Write a creative ad for the following product to run on Facebook aimed at parents:<br>
Product: Learning Room is a virtual environment to help students from kindergarten to high school excel in school..<br><br>
 
	  </p>
    </li>
    <li class="filterDiv copywriting support">
      <span class="accordion-thumb">Reviews - <a style="color:#fff" href='https://platform.openai.com/examples/default-restaurant-review'>API LINK</a></span>
      <p class="accordion-panel">
		  Write a...review based on these notes.../ Classify the sentiment in these...<br><br>

WRITE:<br><br>
Write a restaurant review based on these notes:<br>
Name: The Blue Wharf<br>
Lobster great, noisy, service polite, prices good.<br>

CLASSIFY:<br><br>
Classify the sentiment in these tweets (negative, posstive etc) / Decide whether a Tweet's sentiment is positive, neutral, or negative.<br><br>

1. Import reviews<br>
2. Get it to classify text<br>
3. Show sentiment (e.g. 50% postive etc)<br>
 
	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Recipes - <a style="color:#fff" href='https://platform.openai.com/examples/default-recipe-generator'>API LINK</a></span>
      <p class="accordion-panel">
		  Create me recipe with...<br><br>

Examples:<br><br>
Write a recipe based on these ingredients and instructions:<br>
 
	  </p>
    </li>
    <li class="filterDiv copywriting generation">
      <span class="accordion-thumb">Sales Pitch</span>
      <p class="accordion-panel">
		  Can you create me a sales pitch for...<br><br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Spreadsheet Creator - <a style="color:#fff" href='https://platform.openai.com/examples/default-spreadsheet-gen'>API LINK</a></span>
      <p class="accordion-panel">
		  A two-column spreadsheet of top science fiction movies and the year of release:<br><br>

Title |  Year of release

	  </p>
    </li>
    <li class="filterDiv copywriting answers">
      <span class="accordion-thumb">Summarize</span>
      <p class="accordion-panel">

Summarize for a 2nd grader (Translates difficult text into simpler concepts)<br><br>
Summarize this article in less than 200 words: (article link)<br><br>

	  </p>
    </li>
    <li class="filterDiv generation answers">
      <span class="accordion-thumb">Sightseeing</span>
      <p class="accordion-panel">
		  Where can I see.../ suggest me tourist places in...<br><br>

	  </p>
    </li>
    <li class="filterDiv generation creative">
      <span class="accordion-thumb">Screenplays</span>
      <p class="accordion-panel">
		  Write me a screenplay about...<br><br>

	  </p>
    </li>
    <li class="filterDiv generation creative">
      <span class="accordion-thumb">Stories - <a style="color:#fff" href='https://platform.openai.com/examples/default-micro-horror'>API LINK</a></span>
      <p class="accordion-panel">
		  Creates two to three sentence...<br><br>

Examples:<br><br>
Creates two to three sentence short horror stories from a topic input.<br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Stories - <a style="color:#fff" href='https://platform.openai.com/examples/default-translate'>API LINK</a></span>
      <p class="accordion-panel">
		  Translate this into...<br><br>

Examples:<br><br>
Translate this into 1. French, 2. Spanish and 3. Japanese: What rooms do you have available<br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Movie to Emoji - <a style="color:#fff" href='https://platform.openai.com/examples/default-movie-to-emoji'>API LINK</a></span>
      <p class="accordion-panel">

Convert movie titles into emoji (use for quiz?)<br>

	  </p>
    </li>
    <li class="filterDiv copywriting creative">
      <span class="accordion-thumb">Speeches</span>
      <p class="accordion-panel">

Write me a wedding speech about...<br>

	  </p>
    </li>
    <li class="filterDiv copywriting marketing">
      <span class="accordion-thumb">Video</span>
      <p class="accordion-panel">

Video Titles<br>
Youtube Tags Generator<br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Daily Schedule</span>
      <p class="accordion-panel">

Create a daily work schedule for a content creator where I wake up at 6:30 and want to enjoy a 45-minute lunch.

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Travel Guide</span>
      <p class="accordion-panel">

Act as a travel guide, providing recommendations and suggestions for things to see and do in Barcelona.<br><br>

	  </p>
    </li>
    <li class="filterDiv generation">
      <span class="accordion-thumb">Legal</span>
      <p class="accordion-panel">

I am considering suing my former employer for wrongful termination. What are my options and what evidence do I need to gather?<br><br>

	  </p>
    </li>
    <li class="filterDiv education">
      <span class="accordion-thumb">Study Buddy</span>
      <p class="accordion-panel">

I don’t know how to solve this math problem: -5 + 3x = 11. Could you please walk me through the process?

	  </p>
    </li>
    <li class="filterDiv education">
      <span class="accordion-thumb">Tests & Exams</span>
      <p class="accordion-panel">

I have a 9th-grade biology quiz coming up that I need to practice for. Could you please send me 5 multiple-choice questions and 5 long-form questions to practice with?

	  </p>
    </li>
    <li class="filterDiv education">
      <span class="accordion-thumb">Languages</span>
      <p class="accordion-panel">

Could assist me in practicing a basic conversation in French where we talk to each other. Assume we’ve just met. You will initiate the conversation and wait for my response. Please translate every response into English.

	  </p>
    </li>
    <li class="filterDiv answers">
      <span class="accordion-thumb">Health & Fitness</span>
      <p class="accordion-panel">

1. Meals<br>
Create a 7-day meal plan for an adult who needs to consume 2500 calories per day, but make it vegan-friendly<br>
I am looking for a vegan recipe that includes avocado, can you suggest something?<br>
Suggest recipes that include:<br>
Create a grocery list to cook: <br><br>

2. Excercise<br>
What are some exercises I can do at home with just a yoga mat and resistance bands?<br>
What's the best workout plan for someone in there 40's.<br>
Create a 5-day workout plan for an adult that included 3 strength training sessions and 2 cardio training sessions.<br><br>

3. Health<br>
I have high blood pressure and I want to lower it. What can I do?<br>
I have high blood sugar and I want to lower it. What can I do?<br>
What are some healthy snacks that are under 150 calories?” or “How many calories are in a serving of broccoli?<br>
What are the macro and micronutrient content of an avocado?<br>
What are some breathing exercises for reducing stress and anxiety?<br><br>

	  </p>
    </li>
    <li class="filterDiv generation copywriting marketing">
      <span class="accordion-thumb">Content</span>
      <p class="accordion-panel">
	  
Give me list of article titles for the topic or industry<br><br>
Give me list of video ideas for the topic or industry<br><br>
Give me list of social media post for the topic or industry<br><br>
Make the following more exciting and geared towards busy professionals: [paste the text]<br><br>
Generate a slogan for a new line of eco-friendly cleaning products<br><br>

	  </p>
    </li>
	
  </ul>
  
</div>
