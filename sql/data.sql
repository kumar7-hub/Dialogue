SET foreign_key_checks = 0;

-- Data for User Table 
INSERT INTO User (username, password, email) VALUES 
('admin', 'admin', 'admin@gmail.com'),
('bob3', 'bob', 'bob3@icloud.com'),
('skumar', 'admin', 'skumar@dialogue.com');

-- Data for Category Table
INSERT INTO Category (name) VALUES
('Technology'),
('Travel'),
('Food'),
('Lifestyle'),
('Cars'), 
('Sports');

-- Data for Post Table
INSERT INTO Post (uid, cid, title, content) VALUES
-- Technology
(3, 1, 'Is AI going to replace developers?', 'I am curious if AI tools like Copilot will eventually make coding obsolete. As someone who just started learning programming, I wonder if this is a long-term career.'),
(2, 1, 'What is the best budget laptop for programming?', 'Looking for something affordable but efficient for coding tasks. I am a student learning Python and I do not want to overspend.'),
(2, 1, 'Thoughts on the latest iPhone?', 'The new iPhone just dropped. Is it worth upgrading from the last generation? I currently use an iPhone 11 and thinking about switching.'),
(3, 1, 'Best resources to learn web development?', 'Can anyone recommend solid free courses or tutorials for web development? I am switching careers and want to start freelancing.'),
(3, 1, 'Do you really need a mechanical keyboard?', 'I keep seeing people rave about them. Are they worth it for programming? I type a lot daily and wonder if it makes a real difference.'),
(1, 1, 'Quantum computing explained simply?', 'Can someone ELI5 what quantum computing really means? I have read some articles but it still feels like science fiction.'),
(1, 1, 'Android vs iOS development?', 'Which platform is better to start building apps on and why? I have experience with Java but I own an iPhone.'),
(2, 1, 'What is edge computing used for?', 'Trying to understand how edge computing differs from cloud computing. I heard it is important for IoT devices.'),
(3, 1, 'Do coding bootcamps actually work?', 'Would love to hear real experiences from people who tried bootcamps. I am considering one but not sure if it is worth the cost.'),
(1, 1, 'Best note-taking apps for students?', 'I want to digitize my study notes. Any suggestions? I am juggling multiple classes and need something easy to organize.'),

-- Travel
(2, 2, 'Best places to visit in Japan?', 'Planning a trip to Japan next spring. Looking for recommendations. It is my first time traveling there and I want to make the most of it.'),
(1, 2, 'Is it worth getting TSA PreCheck?', 'I fly a few times a year. Not sure if it is worth the cost. Security lines always stress me out.'),
(3, 2, 'Solo travel tips for beginners?', 'Thinking of going solo for the first time. What should I watch out for? I have only traveled with friends or family before.'),
(2, 2, 'Overrated tourist spots?', 'What places did you visit that did not live up to the hype? I want to avoid wasting time on my next trip.'),
(1, 2, 'Best budget airlines in Europe?', 'Looking for the cheapest way to country-hop around Europe. I am planning a backpacking trip this summer.'),
(3, 2, 'What is in your travel bag?', 'Lets talk travel essentials. What do you never leave without? I always forget something important.'),
(1, 2, 'How do you avoid jet lag?', 'Every long flight ruins my first few days. Any cures? I travel across time zones often for work.'),
(2, 2, 'Should I get travel insurance?', 'Never bought it before, but I am traveling internationally soon. I am wondering if it is actually useful.'),
(3, 2, 'Coolest hidden gems in the U.S.?', 'I want to explore places most tourists skip. I am doing a road trip this fall and love offbeat spots.'),
(1, 2, 'Is Airbnb still worth it?', 'It used to be cheaper than hotels. Now I am not so sure. Fees seem to keep going up.'),

-- Food
(1, 3, 'Best instant ramen hacks?', 'I want to spice up my ramen game. Any suggestions? I eat it often and want to try something new without spending a lot.'),
(3, 3, 'What is one dish everyone should learn to cook?', 'Looking for ideas that are easy and impressive. I recently moved out and want to cook more for myself.'),
(2, 3, 'Your go-to midnight snack?', 'I am hungry at 1AM and need ideas. I usually eat chips but want better options.'),
(2, 3, 'How do you make the perfect fried rice?', 'Mine always turns out soggy. I love fried rice but cannot get the texture right.'),
(1, 3, 'Best budget-friendly recipes for students?', 'Trying to save money while still eating decent meals. I am in college and tired of fast food.'),
(3, 3, 'What is the most overrated fast food chain?', 'For me, it is Five Guys. Thoughts? Their prices feel too high for what you get.'),
(1, 3, 'How do you meal prep efficiently?', 'I want to eat healthier but I am lazy. Tips? I usually give up after a few days.'),
(2, 3, 'Is organic food actually better?', 'Or is it just marketing hype? I am trying to eat clean but not sure if it is worth it.'),
(3, 3, 'Weirdest food combo you actually love?', 'I swear by peanut butter on burgers. Curious what odd combos others enjoy.'),
(2, 3, 'How to get started with baking?', 'Never baked anything in my life. Where do I start? I want to make cookies or something simple.'),

-- Lifestyle
(1, 4, 'Morning routines that changed your life?', 'Trying to build better habits. What worked for you? I struggle with starting my day productively.'),
(2, 4, 'How to stay off your phone more?', 'I waste hours scrolling daily. Need solutions. I tried deleting apps but always reinstall them.'),
(3, 4, 'Favorite productivity tools?', 'I love Notion but looking for other recommendations. I use it for planning but want more options for focus.'),
(1, 4, 'How to stay consistent at the gym?', 'Motivation always fades after 2 weeks. I want to build a real routine this time.'),
(2, 4, 'Books that changed your perspective?', 'Looking for life-changing reads. I want something inspiring or eye-opening.'),
(3, 4, 'How do you organize your week?', 'I feel like I am always behind. Looking for tips to manage time better.'),
(1, 4, 'Decluttering tips?', 'My apartment is a mess and I do not know where to begin. It is stressing me out.'),
(2, 4, 'Is journaling worth the time?', 'People say it helps. Does it really? I am considering starting a daily journal.'),
(3, 4, 'How to sleep better?', 'I always wake up tired no matter how much I sleep. Could be my habits or environment.'),
(2, 4, 'Balancing work and social life?', 'How do you avoid burnout but still have fun? I work long hours and barely see friends.'),

-- Cars
(1, 5, 'Best first car for new drivers?', 'Looking for something safe, reliable, and affordable. I just got my license and need a recommendation.'),
(2, 5, 'Thoughts on electric vehicles?', 'Are they really as eco-friendly as advertised? I am considering one for my next car.'),
(3, 5, 'Manual vs automatic?', 'Which one do you prefer and why? I learned on an automatic but curious about manual.'),
(1, 5, 'How often should you change your oil?', 'Every 3,000 miles or is that a myth? I just bought my first car and want to take care of it.'),
(2, 5, 'Best car mods under $500?', 'I want to upgrade without breaking the bank. Mainly interested in style or sound.'),
(3, 5, 'What is your dream car?', 'Mine is the Nissan GT-R. Curious what others dream of owning.'),
(2, 5, 'How do you get into car flipping?', 'Looks like a fun side hustle. Wondering where to start and how much capital is needed.'),
(1, 5, 'Tips for buying a used car?', 'I am scared of getting scammed. Planning to buy from a private seller.'),
(3, 5, 'Do you really need premium gas?', 'Or is regular fine for most cars? My car manual says regular but people say premium is better.'),
(2, 5, 'Is leasing a car a bad idea?', 'Trying to decide between leasing and buying. I drive about 12,000 miles a year.'),

-- Sports
(3, 6, 'Is pickleball really that fun?', 'Everyone keeps talking about it. Should I try? I see people of all ages playing at my local park.'),
(2, 6, 'Best workout routines for beginners?', 'Trying to get back into shape. Where do I start? I have not exercised in months.'),
(1, 6, 'Who is the GOAT in basketball?', 'LeBron or MJ? Lets settle this. I grew up watching LeBron but MJ has the legacy.'),
(3, 6, 'How do you stay motivated to run?', 'Every time I start, I quit after a week. I want to train for a 10k but can never stay consistent.'),
(2, 6, 'What is the best home gym equipment?', 'I want a setup that does not take up much space. I live in a small apartment.'),
(1, 6, 'Thoughts on VAR in soccer?', 'Does it ruin the flow of the game or improve fairness? I have mixed feelings after watching a few recent matches.'),
(2, 6, 'How do I train for my first 5k?', 'Goal is to run it without walking. I have never done a race before.'),
(3, 6, 'Best sport for overall fitness?', 'Trying to find something fun and effective. I want to stay in shape without going to the gym.'),
(1, 6, 'Fantasy football tips?', 'I always end up last. Help me out. My draft picks never seem to work out.'),
(2, 6, 'Is stretching before workouts outdated?', 'Some say to stretch after instead. What is right? I want to prevent injury.');

-- Data for Comments Table
INSERT INTO Comments (pid, uid, comment) VALUES
(1, 2, 'I wonder the same! It feels like AI can help, but I doubt it will fully replace real developers.'),
(1, 1, 'AI tools are cool, but understanding core concepts will always matter.'),
(2, 1, 'I used a budget Lenovo for coding and it worked great for Python projects.'),
(2, 3, 'Look into refurbished models! You can get powerful specs for cheap.'),
(3, 1, 'I upgraded from the 11 and honestly, the difference was bigger than I expected.'),
(3, 3, 'Depends what you use your phone for. The camera is amazing though.'),
(4, 2, 'freeCodeCamp and The Odin Project are great for web dev starters!'),
(4, 1, 'I switched careers too, YouTube channels like Traversy Media really helped me.'),
(5, 2, 'Mechanical keyboards definitely make typing more satisfying. Worth trying one.'),
(5, 1, 'Switched a year ago and now I cannot go back. Huge difference for long typing sessions.'),
(6, 2, 'Quantum computing is basically using probabilities instead of binary logic. Mind-blowing stuff.'),
(6, 3, 'Think of it like spinning coins instead of just heads or tails, kinda.'),
(7, 2, 'Since you know Java, Android might feel more natural, but Swift is really fun too!'),
(8, 3, 'Edge computing brings processing closer to the data source. Faster for IoT.'),
(8, 1, 'I learned that for realtime apps like smart cars, edge is essential.'),
(9, 2, 'Bootcamps work if you put in the work. I landed a dev job after mine.'),
(10, 3, 'Notion is amazing for study notes. You can organize everything neatly.'),
(11, 1, 'Visit Kyoto and Nara! Way more peaceful than Tokyo and full of culture.'),
(11, 3, 'Definitely go to Osaka too. The food scene there is incredible!'),
(12, 2, 'TSA PreCheck saves so much stress during travel. Worth every penny.'),
(13, 2, 'Stay at hostels with good reviews. Great way to meet people when solo traveling.'),
(13, 1, 'First time solo can be intimidating but also super freeing. Plan ahead!'),
(14, 1, 'The Hollywood Walk of Fame was underwhelming to me. Just super crowded.'),
(15, 2, 'Ryanair is super cheap but be ready for extra fees. Plan carefully.'),
(15, 3, 'I flew with EasyJet across Europe. Solid option if you pack light.'),
(17, 2, 'Landing in the morning local time helps reset your body clock.'),
(17, 3, 'Melatonin and staying awake until night helps me fight jet lag.'),
(18, 3, 'Travel insurance saved me when my luggage got lost. Worth it.'),
(18, 1, 'Peace of mind alone makes travel insurance a good idea for big trips.'),
(19, 2, 'Sedona, Arizona was a hidden gem for me. Absolutely beautiful and quiet.'),
(19, 1, 'Check out Door County, Wisconsin! Underrated and super cozy.'),
(20, 3, 'Sometimes hotels offer better value now. Airbnb fees can be ridiculous.'),
(22, 2, 'Everyone should know how to make a basic pasta dish. Easy and impressive.'),
(23, 1, 'Peanut butter on toast with banana slices is the best midnight snack ever.'),
(24, 1, 'Use cold, day old rice for perfect fried rice! That made a huge difference for me.'),
(24, 3, 'High heat and a little sesame oil are game changers for fried rice texture.'),
(25, 2, 'Rice and beans combos can be cheap, healthy, and filling for students.'),
(26, 1, 'I agree about Five Guys! Pricey for what it is. In-N-Out is still king.'),
(29, 2, 'I put hot sauce on ice cream sometimes. Sounds weird but it slaps.'),
(30, 1, 'Start with simple chocolate chip cookies. Foolproof and delicious.'),
(30, 3, 'Baking is all about measuring right. Cookies are a great first step.'),
(31, 2, 'Waking up and making my bed first thing helped me start better days.');

-- Data for Likes Table
INSERT INTO Likes (uid, pid) 
SELECT uid, pid FROM Comments;

SET foreign_key_checks = 1;