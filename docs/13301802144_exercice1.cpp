/*
 * Exercice pour verifier les parentheses
 */
#include<iostream>
#include<string>
#include<stack>
#include<unordered_set>
#include<map>
#include <assert.h>
using namespace std;

bool isValid(string S)
{
  //stack of char
  stack<char> stackk;
  map<char,char> OurMap{{')','('}, {'}','{'},     //  example of map :  map<int, string> sample_map { { 1, "one"}, { 2, "two" } };
                   {']','['}};
  for(auto c: S)
  {
    //caracetere  a traiter
    if( c== '(' || c == '{'  || c == '[')
        stackk.push(c);
    else if ( c== ')' || c == '}' || c == ']')
    {
      if(stackk.empty() || OurMap[c] != stackk.top())
        return false;
      else
        stackk.pop();
    }
  }
     return stackk.empty();
      
  }


int main (int argc, char *argv[])
{
  
  assert(isValid("(a+b)+{c+d}")== true);
  assert(isValid("{(a+[b+c])} + d")== true);
  assert(isValid("((a + b)")== false);
  assert(isValid("((a + b)))")== false);
  assert(isValid("(a+b}")== false);
  return 0;
}
