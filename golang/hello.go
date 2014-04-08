package main

import (
	"fmt"
	"math/rand"
	"time"
)

func main() {
	msg := make(chan string)
	end := make(chan string)
	go girlStory(msg, end)
	go boyStory(msg, end)
	fmt.Println(<- end)
}

func boyStory(msg, end chan string) {
	sendMsg("boy", "girl", "hi!", "happy", msg)
	for {
		switch m := <- msg; {
		case m == "hi!": sendMsg("boy", "girl", "hi!", "miss", msg)
		case m == "...": sendMsg("boy", "girl", "hi!", "angry", msg)
		case m == "hi~": sendMsg("boy", "girl", "a u happy?", "exciting", msg)
		case m == "yes, i am happy": sendMsg("boy", "girl", "ok,nice", "so sad", msg)
		case m == "i am lonely...": sendMsg("boy", "girl", "be my love?", "exciting!", msg)
		case m == "ok, why not": end <- "---May happy ending of love belong to every developer. ---"
		}
		time.Sleep(time.Duration(1e9))
	}
}

func girlStory(msg, end chan string) {
	rand.Seed(time.Now().UnixNano())
	for {
		happy := rand.Intn(5) < 3
		alone := rand.Intn(5) == 0
		switch m := <- msg; {
		case m == "hi!":
			if happy {
				sendMsg("girl", "boy", "hi~", "happy", msg)
			} else {
				sendMsg("girl", "boy", "hi!", "sad", msg)
			}
		case m == "a u happy?":
			if !happy &&  alone {
				sendMsg("girl", "boy", "i am lonely...", "sad", msg)
			} else if !happy {
				sendMsg("girl", "boy", "...", "sad", msg)
			} else {
				sendMsg("girl", "boy", "yes, i am happy", "happy", msg)
			}
		case m == "ok,nice":
			sendMsg("girl", "boy", "...", "-_-", msg)
		case m == "be my love?":
			sendMsg("girl", "boy", "ok, why not", "happy", msg)
		}
		time.Sleep(time.Duration(1e9))
	}
}

func sendMsg(from, to, body, feeling string, msg chan string) {
	fmt.Println(from, "(", feeling, "): ", body)
	msg <- body
}