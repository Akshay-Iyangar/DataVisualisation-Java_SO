
#Adaptive Web Assignment 1
#reading all the .csv files

click_data<- read.csv("class_click.csv",header = TRUE)
op<-read.csv("class_operation.csv",header= TRUE)
operation_data <- read.csv("operation1.csv",header = TRUE)
query_data <- read.csv("query.csv",header = TRUE)
select_data <- read.csv("class_select.csv",header= TRUE)

word<-read.csv("word.csv",header=FALSE)

#open csv and avoid string as factors
library(tm)

query<-read.csv("class_query.csv",stringsAsFactors = FALSE)

str(query)

query_text<-paste(query$query,collapse = " ")

query_source<-VectorSource(query_text)

corpus<-Corpus(query_source)
corpus<-tm_map(corpus,content_transformer(tolower))

corpus <- tm_map(corpus, removePunctuation)
corpus <- tm_map(corpus, stripWhitespace)
corpus <- tm_map(corpus, removeWords, (stopwords(“english”)))

extractTitle <- function(name,index){
  name <- as.character(name)
  word_var<-NULL
  
  for(i in 1:nrow(word)){
  
    if(length(grep(word[i,1],name,ignore.case = TRUE))>0)
    {
      word_var<-c(word_var,word[i,1])
    }
  }
}

word<-read.csv("word.csv",header = TRUE)
word_count<-read.csv("word_count.csv",header = TRUE)
word_query<-read.csv("query_word.csv",header = TRUE)

word_query$u_id<-unique(query_data$u_id)

for(k in 1:nrow(word_query)){
  temp<-query_data[which(query_data$u_id==as.character(word_query$u_id[k])),]
  word_count$u_id[k]<-word_query$u_id[k]
  word_var=NULL
  count=0
  for(i in 1:nrow(word)){
    for(j in 1:nrow(temp)){
      if(length(grep(as.character(word[i,1]),as.character(temp$query[j]),ignore.case = TRUE))>0)
      {
        word_var<-c(word_var,as.character(word[i,1]))
        count=count+1
      }
    }
    word_count[k,(i+1)]<-count
  }
  word_var<-paste(word_var,collapse = " ")
  word_var<-lapply(strsplit(word_var," "),unique)
  word_query$words[k]<-word_var
}


temp<-query_data[which(query_data$u_id==as.character(word_query$u_id[1])),]
word_var=NULL
for(j in 1:nrow(word)){
  if(length(grep(as.character(word[j,1]),as.character(temp$query[1]),ignore.case = TRUE))>0)
  {
    word_var<-c(word_var,as.character(word[j,1]))
    
  }
}



