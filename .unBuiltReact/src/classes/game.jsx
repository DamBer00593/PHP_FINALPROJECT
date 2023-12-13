export class Game{
    game= {
        frame1:{
            ball1:0,
            ball2:0
        },
        frame2:{
            ball1:0,
            ball2:0
        },
        frame3:{
            ball1:0,
            ball2:0
        },
        frame4:{
            ball1:0,
            ball2:0
        },
        frame5:{
            ball1:0,
            ball2:0
        },
        frame6:{
            ball1:0,
            ball2:0
        },
        frame7:{
            ball1:0,
            ball2:0
        },
        frame8:{
            ball1:0,
            ball2:0
        },
        frame9:{
            ball1:0,
            ball2:0
        },
        frame10:{
            ball1:0,
            ball2:0
        },
        frame11:{
            ball1:0,
            ball2:0
        },
        frame12:{
            ball1:0,
        }
    }
    gameScore= {
        frame1:0,
        frame2:0,
        frame3:0,
        frame4:0,
        frame5:0,
        frame6:0,
        frame7:0,
        frame8:0,
        frame9:0,
        frame10:0
    }

    current= null;

    constructor(gameID, matchID, gameNumber, gameState, balls, playerID){
        this.gameID=gameID;
        this.gameNumber=gameNumber; this.matchID=matchID;
        this.gameState = gameState;
        if(balls != null){
            this.game=JSON.parse(balls);
        }
        this.playerID=playerID
    }


    toTable(){
        let htmlstring = "";
        for(let i =1; i<11; i++){
            let score=this.gameScore["frame"+i];
            let ball1=this.game["frame"+i]["ball1"];
            let ball2=this.game["frame"+i]["ball2"];
            let final;
            if(ball1 === "X"){
                final="X";
            }
            else if(this.game["frame"+i]["ball2"] === "/"){
                final="/";
            }
            else{
                final=ball1+"/"+ball2;
            }
            htmlstring+= "<tr><td>" + score + "</td><td>" + final + "</td></tr>";
           
            if(i===10){
                //if 10th frame is a spare
                if(final==="/"){
                    final=this.game["frame11"]["ball1"];
                    if(final==="X"){
                        score+=10;
                    }
                    htmlstring+= "<tr><td>" + score + "</td><td>" + final + "</td></tr>";
                }
                //if 10th frame is a strike 
                else if(final==="X"){
                    ball1=this.game["frame11"]["ball1"];
                    //if 11th frame is a strike
                    if(ball1==="X"){
                        score+=10;
                        htmlstring+= "<tr><td>" + (score+10) + "</td><td>" + final + "</td></tr>";
                        ball2=this.game["frame12"]["ball1"];
                        //if 12th frame is a strike
                        if(ball2==="X"){
                            htmlstring+= "<tr><td>" + score+10 + "</td><td>" + final + "</td></tr>";
                        }
                        else{
                            htmlstring+= "<tr><td>" + score+ball2 + "</td><td>" + final + "</td></tr>";
                        }
                    }
                    ball2=this.game["frame11"]["ball2"];
                    if(ball2==="/"){
                        htmlstring+= "<tr><td>" + score+10 + "</td><td>" + final + "</td></tr>";
                    }
                }
            }
        }
        return htmlstring;
    }


    setScore(){
        for(let i=1; i<11; i++){
            let ball1=this.game["frame"+i]["ball1"];
            let ball2=this.game["frame"+i]["ball2"];
            let ball3;
            //if ball one STRIKE
            if(ball1==="X"){
                ball2=this.game["frame"+(i+1)]["ball1"];
                
                //if the next ball is also a strike
                if(ball2==="X"){
                    ball2=10;
                    ball3=this.game["frame"+(i+2)]["ball1"];
                    //if the last ball is also a strike
                    if (ball3==="X"){
                        ball3=10;
                        this.gameScore["frame"+i]=30
                    }
                    //if the first 2 are strikes and the third isnt
                    else{
                        this.gameScore["frame"+i]=20+ball3;
                    }
                }
                //if the first ball is a strike and the second isnt
                else{
                    ball3=this.game["frame"+(i+1)]["ball2"];
                    //if the first ball is a strike and the third is a spare
                    if(ball3==="/"){
                        ball3=10;
                        this.gameScore["frame"+i]=20;
                    }
                }
                //if the first one is a strike and teh next 2 are nothing special
                this.gameScore["frame"+i]=10+ball2+ball3;
            }
            //if the second ball is a spare
            else if (ball2==="/"){
                ball3=this.game["frame"+(i+2)]["ball1"];
                //if the third ball is a strike
                if(ball3==="X"){
                    ball3=10;
                }
                this.gameScore["frame"+i]=10+ball3;
            }
            else{
                this.gameScore["frame"+i]=ball1+ball2;
            }
            if(i>1){
                this.gameScore["frame"+i] += this.gameScore["frame"+(i-1)];
            }
        }
    }

    setBall(frame, ball, score){
        if (ball===2){
            if(score<this.game["frame"+frame]["ball1"]){
                return false;
            }
            if(score === 10){
                score="/";
            }
        }
        else{
            if (score===10){
                score="X";
            }
        }
        this.game["frame"+frame]["ball"+ball] = score;
        this.setScore();
        return true;
    }

    toDB(){
        let score=0;
        for(let i =1; i<11; i++){
            let s = this.gameScore["frame"+i]
            if(s>score){
                score=s;
            }
        }
        return {
            gameID:this.gameID,
            matchID: this.matchID,
            gameNumber: this.gameNumber,
            gameStateID: this.gameState,
            score:score,
            balls:JSON.toString(this.game),
            playerID: this.playerID
        }
    }
    
}