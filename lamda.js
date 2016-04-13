var https = require('https');
/**
 * This sample demonstrates a simple skill built with the Amazon Alexa Skills Kit.
 * The Intent Schema, Custom Slots, and Sample Utterances for this skill, as well as
 * testing instructions are located at http://amzn.to/1LzFrj6
 *
 * For additional samples, visit the Alexa Skills Kit Getting Started guide at
 * http://amzn.to/1LGWsLG
 */

// Route the incoming request based on type (LaunchRequest, IntentRequest,
// etc.) The JSON body of the request is provided in the event parameter.
exports.handler = function (event, context) {
    try {
        console.log("event.session.application.applicationId=" + event.session.application.applicationId);

        /**
         * Uncomment this if statement and populate with your skill's application ID to
         * prevent someone else from configuring a skill that sends requests to this function.
         */
        /*
        if (event.session.application.applicationId !== "amzn1.echo-sdk-ams.app.[unique-value-here]") {
             context.fail("Invalid Application ID");
        }
        */

        if (event.session.new) {
            onSessionStarted({requestId: event.request.requestId}, event.session);
        }

        if (event.request.type === "LaunchRequest") {
            onLaunch(event.request,
                event.session,
                function callback(sessionAttributes, speechletResponse) {
                    context.succeed(buildResponse(sessionAttributes, speechletResponse));
                });
        } else if (event.request.type === "IntentRequest") {
            onIntent(event.request,
                event.session,
                function callback(sessionAttributes, speechletResponse) {
                    context.succeed(buildResponse(sessionAttributes, speechletResponse));
                });
        } else if (event.request.type === "SessionEndedRequest") {
            onSessionEnded(event.request, event.session);
            context.succeed();
        }
    } catch (e) {
        context.fail("Exception: " + e);
    }
};

/**
 * Called when the session starts.
 */
function onSessionStarted(sessionStartedRequest, session) {
    console.log("onSessionStarted requestId=" + sessionStartedRequest.requestId +
        ", sessionId=" + session.sessionId);
}

/**
 * Called when the user launches the skill without specifying what they want.
 */
function onLaunch(launchRequest, session, callback) {
    console.log("onLaunch requestId=" + launchRequest.requestId +
        ", sessionId=" + session.sessionId);

    // Dispatch to your skill's launch.
    getWelcomeResponse(callback);
}

/**
 * Called when the user specifies an intent for this skill.
 */
function onIntent(intentRequest, session, callback) {
    console.log("onIntent requestId=" + intentRequest.requestId +
        ", sessionId=" + session.sessionId);

    var intent = intentRequest.intent,
        intentName = intentRequest.intent.name;

    // Dispatch to your skill's intent handlers
    if ("GetSurfReportIntent" === intentName) {
        GetSurfReport(intent, session, callback);
    } else {
        throw "Invalid intent";
    }
}

/**
 * Called when the user ends the session.
 * Is not called when the skill returns shouldEndSession=true.
 */
function onSessionEnded(sessionEndedRequest, session) {
    console.log("onSessionEnded requestId=" + sessionEndedRequest.requestId +
        ", sessionId=" + session.sessionId);
    // Add cleanup logic here
}

// --------------- Functions that control the skill's behavior -----------------------

function getWelcomeResponse(callback) {
    // If we wanted to initialize the session to have some attributes we could add those here.
    var sessionAttributes = {};
    var cardTitle = "Welcome";
    var speechOutput = "Welcome to the Alexa Skills Kit sample. " +
        "Please tell me your favorite color by saying, my favorite color is red";
    // If the user either does not reply to the welcome message or says something that is not
    // understood, they will be prompted again with this text.
    var repromptText = "Please tell me your favorite color by saying, " +
        "my favorite color is red";
    var shouldEndSession = false;

    callback(sessionAttributes,
        buildSpeechletResponse(cardTitle, speechOutput, repromptText, shouldEndSession));
}

/**
 * Sets the color in the session and prepares the speech to reply to the user.
 */
function GetSurfReport(intent, session, callback) {
    
    //CALL API
    call_weather_api(intent.slots.location.value,function(res){
        if(!res){
            var cardTitle = intent.name;
            var repromptText = "I'm Sorry Bro";
            var sessionAttributes = {};
            var shouldEndSession = true;
            var speechOutput = "I don't have a information on that location";
            callback(sessionAttributes,
                buildSpeechletResponse(cardTitle, speechOutput, repromptText, shouldEndSession));
        }else{
            //LOCATION PASSES
            var cardTitle = intent.name;
            var repromptText = "Right on";
            var sessionAttributes = {};
            var shouldEndSession = true;
            var speechOutput = "The Surf today in "+intent.slots.location.value+" is ";
            //PASS RELEVENT INFORMATION
            var d = res.data.weather[0].hourly[0];
            var swell_height_feet = d.swellHeight_m*3;
            swell_height_feet = swell_height_feet.toFixed(1);
            var sets_feet = d.sigHeight_m*3;
            sets_feet =sets_feet.toFixed(1);
            var winds = '';
            var wind_speed = d.windspeedMiles;
            if(d.winddir16Point =='S'){
                winds = 'South'; 
            }else if(d.winddir16Point == 'SW'){
                winds = 'South West';
            }else if(d.winddir16Point =='SE'){
                winds = 'South East';
            }else if(d.winddir16Point =='E'){
                winds = 'East';
            }else if(d.winddir16Point =='NE'){
                winds = 'North East';
            }else if(d.winddir16Point =='N'){
                winds = 'North';
            }else if(d.winddir16Point =='NW'){
                winds = 'North West';
            }else if(d.winddir16Point =='NNW'){
                winds = 'North North West';
            }else if(d.winddir16Point =='WNW'){
                winds = 'West North West';
            }else if(d.winddir16Point =='SSW'){
                winds = 'South South West';
            }else if(d.winddir16Point =='WSW'){
                winds = 'West South West';
            }else if(d.winddir16Point =='ESE'){
                winds = 'East South East';
            }else if(d.winddir16Point =='ENE'){
                winds = 'East North East';
            }else if(d.winddir16Point =='SSE'){
                winds = 'South South East';
            }else{
                winds ='Sky';
            }
            //CONTINUE SPEECH OUTPUT
            speechOutput += swell_height_feet+' feet with sets as large as '+sets_feet+' feet. Winds are out of the '+winds;
            speechOutput += ' at '+wind_speed+' Miles Per Hour'; 
            
            callback(sessionAttributes,
                 buildSpeechletResponse(cardTitle, speechOutput, repromptText, shouldEndSession));
        }
    });
}

// --------------- Helpers that build all of the responses -----------------------

function call_weather_api(location,callback){
   build_url(location,function(url){
        //CHECK IF WE GOT A URL
        if(!url){
            callback(false);
        }
        //REQUEST AND LOG
        https.get('https://api.worldweatheronline.com/free/v2/marine.ashx?'+url,function(res){
            console.log("\nstatus code: ", res.statusCode);
            res.on('data', function(data) {
                callback( JSON.parse(data) );
            });
        });
    });
}


function buildSpeechletResponse(title, output, repromptText, shouldEndSession) {
    return {
        outputSpeech: {
            type: "PlainText",
            text: output
        },
        card: {
            type: "Simple",
            title: "SessionSpeechlet - " + title,
            content: "SessionSpeechlet - " + output
        },
        reprompt: {
            outputSpeech: {
                type: "PlainText",
                text: repromptText
            }
        },
        shouldEndSession: shouldEndSession
    };
}


function build_url(location,callback){
    var url = 'format=json&includelocation=yes&key=a4b8ecc4bbe6713e68b74e63dcd49&';
    if(location == 'New Jersey'){
        callback( url+'q=40%2C-73' );
    }else
    if(location == 'Rhode Island'){
        callback(url+'q=41.5%2C-71.31')
    }else
    if(location == 'New Hampshire'){
        callback(url+'q=42.9%2C-70.88')
    }else{
        callback(false);
    }
}


function buildResponse(sessionAttributes, speechletResponse) {
    return {
        version: "1.0",
        sessionAttributes: sessionAttributes,
        response: speechletResponse
    };
}