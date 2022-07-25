import json
from pathlib import Path

def SessionShare():
    path = Path(__file__).parent
    path /= '../SettingShare.json'
    json_open = open(path, 'r')
    json_load = json.load(json_open)
    return json_load

def SessionRead(cndrl_sID):
    directory = SessionShare()["SessionDumper"]
    path = Path(__file__).parent
    path /= '../../../../'+directory+str(cndrl_sID)+'.json'
    json_open = open(path, 'r')
    json_load = json.load(json_open)
    return json_load

def VariableRead(name):
    path = Path(__file__).parent
    path /= '../Src/'+str(name)+'.json'
    json_open = open(path, 'r')
    json_load = json.load(json_open)
    return json_load