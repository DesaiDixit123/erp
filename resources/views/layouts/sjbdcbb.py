class person:

    def __init__(self,name,age,cource):
        self.name = name
        self.age = age
        self.cource = cource

 
    def fun(self):
        print("my name is " + self.name)
p1 = person("Helly",20,"ml")

p1.fun()